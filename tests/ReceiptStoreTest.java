import com.github.skulzontheyt.maskui.ReceiptStore;
import com.github.skulzontheyt.maskui.ReceiptStore.Receipt;
import com.github.skulzontheyt.maskui.ReceiptStore.State;
import java.nio.file.Files;
import java.util.UUID;

public final class ReceiptStoreTest {
    public static void main(String[] args) throws Exception {
        var directory = Files.createTempDirectory("maskui-receipts-test-");
        var path = directory.resolve("receipts.properties");
        var store = new ReceiptStore(path);
        UUID id = UUID.randomUUID(), buyer = UUID.randomUUID();
        store.create(new Receipt(id, buyer, "dragon", 35000, State.PENDING_PURCHASE));
        check(new ReceiptStore(path).pendingCount() == 1, "pending purchase persisted");
        store.transition(id, State.PENDING_PURCHASE, State.ACTIVE);
        check(new ReceiptStore(path).get(id).buyer().equals(buyer), "buyer persisted");
        check(new ReceiptStore(path).get(id).paid() == 35000, "paid price persisted");
        store.transition(id, State.ACTIVE, State.REDEEMING);
        check(new ReceiptStore(path).get(id).state() == State.REDEEMING, "pending sale persisted across crash");
        store.transition(id, State.REDEEMING, State.ACTIVE);
        store.transition(id, State.ACTIVE, State.REDEEMING);
        store.transition(id, State.REDEEMING, State.SOLD);
        store = new ReceiptStore(path);
        try { store.transition(id, State.ACTIVE, State.REDEEMING); throw new AssertionError("double redeem allowed"); } catch (IllegalStateException expected) {}
        try { store.create(new Receipt(id, buyer, "dragon", 35000, State.PENDING_PURCHASE)); throw new AssertionError("duplicate receipt allowed"); } catch (IllegalStateException expected) {}
        try { store.transition(id, State.SOLD, State.ACTIVE); throw new AssertionError("sold receipt revived"); } catch (IllegalStateException expected) {}
        Files.writeString(directory.resolve("corrupt.properties"), "not-a-uuid=broken");
        try { new ReceiptStore(directory.resolve("corrupt.properties")); throw new AssertionError("corrupt store accepted"); } catch (java.io.IOException expected) {}
        var blocked = directory.resolve("blocked"); Files.writeString(blocked, "not a directory");
        var failed = new ReceiptStore(blocked.resolve("receipts.properties"));
        UUID failedId = UUID.randomUUID();
        try { failed.create(new Receipt(failedId, buyer, "dragon", 35000, State.PENDING_PURCHASE)); throw new AssertionError("write should fail"); } catch (java.io.IOException expected) {}
        check(failed.get(failedId) == null, "failed persistence changed in-memory state");
        System.out.println("RECEIPT_TEST_PASS: persistence, states, replay prevention, corruption and write failure");
    }
    private static void check(boolean condition, String label) { if (!condition) throw new AssertionError(label); }
}
