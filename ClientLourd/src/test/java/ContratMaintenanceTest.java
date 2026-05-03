import java.time.LocalDate;

import static org.junit.jupiter.api.Assertions.assertFalse;
import static org.junit.jupiter.api.Assertions.assertTrue;
import org.junit.jupiter.api.Test;

import com.cashcash.models.ContratMaintenance;

public class ContratMaintenanceTest {

    @Test
    void testIsActive_true() {
        // GIVEN
        ContratMaintenance contrat = new ContratMaintenance();
        contrat.setDateSignature(LocalDate.now().plusDays(-5));
        contrat.setDateEcheance(LocalDate.now().plusDays(5));

        // WHEN
        boolean result = contrat.isActive();

        // THEN
        assertTrue(result);
    }

    @Test
    void testIsActive_false() {
        // GIVEN
        ContratMaintenance contrat = new ContratMaintenance();
        contrat.setDateSignature(LocalDate.now().plusDays(5));
        contrat.setDateEcheance(LocalDate.now().plusDays(10));

        // WHEN
        boolean result = contrat.isActive();

        // THEN
        assertFalse(result);
    }
}
