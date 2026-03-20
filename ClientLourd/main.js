const { app, BrowserWindow } = require('electron');
const { spawn } = require('child_process');
const path = require('path');
const http = require('http');
const fs = require('fs');

let mainWindow;
let phpServerProcess;
const PORT = 8001;
const SERVER_READY_TIMEOUT_MS = 90000;

function buildErrorPage(title, message) {
        return `data:text/html;charset=utf-8,${encodeURIComponent(`<!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <title>${title}</title>
    <style>
        body { font-family: Segoe UI, Arial, sans-serif; margin: 0; padding: 24px; background: #f5f6f8; color: #1f2937; }
        .card { max-width: 760px; margin: 32px auto; background: #fff; border-radius: 10px; border: 1px solid #e5e7eb; padding: 20px; }
        h1 { margin-top: 0; font-size: 20px; }
        p { line-height: 1.5; }
        code { background: #f3f4f6; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>${title}</h1>
        <p>${message}</p>
        <p>Consulte le fichier de log: <code>%APPDATA%\\cashcash-client-lourd\\php-error.log</code></p>
    </div>
</body>
</html>`)}`;
}

function getDatabaseUrl() {
    // Default behavior: let Symfony read DATABASE_URL from .env/.env.local.
    const mode = (process.env.CLIENT_DB_MODE || 'env').toLowerCase();

    if (mode === 'sqlite') {
        // Use a writable location on Windows for packaged apps.
        const sqliteFile = path.join(app.getPath('userData'), 'cashcash.db').replace(/\\/g, '/');
        return `sqlite:///${sqliteFile}`;
    }

    return process.env.DATABASE_URL;
}

function createWindow() {
    mainWindow = new BrowserWindow({
        width: 1200,
        height: 800,
        webPreferences: {
            preload: path.join(__dirname, 'preload.js'),
            nodeIntegration: false,
            contextIsolation: true
        },
        autoHideMenuBar: true
    });

    mainWindow.webContents.on('did-fail-load', (_event, errorCode, errorDescription, validatedURL) => {
        console.error(`Renderer failed to load ${validatedURL}: [${errorCode}] ${errorDescription}`);
    });

    checkServerReady(PORT, () => {
        mainWindow.loadURL(`http://localhost:${PORT}`);
    }, () => {
        mainWindow.loadURL(buildErrorPage(
            'Demarrage incomplet',
            'Le backend local n\'a pas repondu a temps. Verifie Docker, la base PostgreSQL et le log PHP du client lourd.'
        ));
    });

    mainWindow.on('closed', function () {
        mainWindow = null;
    });
}

function startPhpServer() {
    console.log('Starting PHP server...');
    let phpPath;
    let publicDir;

    if (app.isPackaged) {
        phpPath = path.join(process.resourcesPath, 'php', 'php.exe');
        publicDir = path.join(process.resourcesPath, 'app-backend', 'public');
    } else {
        phpPath = path.join(__dirname, 'php-win', 'php.exe');
        publicDir = path.join(__dirname, '..', 'public');
    }
    
    const projectDir = path.join(publicDir, '..');
    
    const env = Object.assign({}, process.env);
    const databaseUrl = getDatabaseUrl();
    if (databaseUrl) {
        env.DATABASE_URL = databaseUrl;
    }

    // Démarrage du serveur PHP intégré
    phpServerProcess = spawn(phpPath, ['-S', `localhost:${PORT}`, '-t', publicDir], {
        cwd: projectDir,
        env: env
    });

    const logPath = path.join(app.getPath('userData'), 'php-error.log');

    fs.writeFileSync(logPath, `Starting PHP at ${phpPath}\nProjectDir: ${projectDir}\nDATABASE_URL: ${env.DATABASE_URL || '[from .env files]'}\n`);

    phpServerProcess.stdout.on('data', (data) => {
        console.log(`PHP stdout: ${data}`);
        fs.appendFileSync(logPath, `STDOUT: ${data}\n`);
    });

    phpServerProcess.stderr.on('data', (data) => {
        console.error(`PHP stderr: ${data}`);
        fs.appendFileSync(logPath, `STDERR: ${data}\n`);
    });

    phpServerProcess.on('close', (code) => {
        console.log(`PHP server process exited with code ${code}`);
        fs.appendFileSync(logPath, `EXIT CODE: ${code}\n`);
    });
}

function checkServerReady(port, onReady, onTimeout) {
    let done = false;

    const timeoutId = setTimeout(() => {
        if (done) {
            return;
        }

        done = true;
        if (onTimeout) {
            onTimeout();
        }
    }, SERVER_READY_TIMEOUT_MS);

    const check = () => {
        if (done) {
            return;
        }

        http.get(`http://localhost:${port}`, (res) => {
            if (res.statusCode >= 200 && res.statusCode < 600) {
                done = true;
                clearTimeout(timeoutId);
                onReady();
            } else {
                setTimeout(check, 500);
            }
        }).on('error', (err) => {
            setTimeout(check, 500);
        });
    };

    check();
}

app.whenReady().then(() => {
    startPhpServer();
    createWindow();

    app.on('activate', function () {
        if (BrowserWindow.getAllWindows().length === 0) createWindow();
    });
});

app.on('window-all-closed', function () {
    if (process.platform !== 'darwin') {
        app.quit();
    }
});

app.on('will-quit', () => {
    if (phpServerProcess) {
        phpServerProcess.kill();
    }
});
