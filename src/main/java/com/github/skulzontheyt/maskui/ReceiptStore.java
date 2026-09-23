package com.github.skulzontheyt.maskui;

import java.io.IOException;
import java.io.StringReader;
import java.io.StringWriter;
import java.nio.ByteBuffer;
import java.nio.channels.FileChannel;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.StandardCopyOption;
import java.nio.file.StandardOpenOption;
import java.util.HashMap;
import java.util.Map;
import java.util.Properties;
import java.util.UUID;

/** Durable, fail-closed receipt journal. Pending states are never replayed automatically. */
public final class ReceiptStore {
    public enum State { PENDING_PURCHASE, ACTIVE, REDEEMING, SOLD, VOID }
    public static final class Receipt {
        private final UUID id, buyer;
        private final String mask;
        private final double paid;
        private final State state;
        public Receipt(UUID id, UUID buyer, String mask, double paid, State state) {
            if (id == null || buyer == null || mask == null || mask.trim().isEmpty() || state == null || !Double.isFinite(paid) || paid < 0)
                throw new IllegalArgumentException("Invalid receipt");
            this.id = id; this.buyer = buyer; this.mask = mask; this.paid = paid; this.state = state;
        }
        public UUID id() { return id; }
        public UUID buyer() { return buyer; }
        public String mask() { return mask; }
        public double paid() { return paid; }
        public State state() { return state; }
        Receipt withState(State state) { return new Receipt(id, buyer, mask, paid, state); }
    }
    private final Path file;
    private Map<UUID, Receipt> receipts = new HashMap<>();

    public ReceiptStore(Path file) throws IOException {
        this.file = file;
        if (!Files.exists(file)) return;
        Properties data = new Properties();
        try {
            data.load(new StringReader(new String(Files.readAllBytes(file), StandardCharsets.UTF_8)));
            for (String key : data.stringPropertyNames()) {
                String[] parts = data.getProperty(key).split("\\|", -1);
                if (parts.length != 4) throw new IllegalArgumentException("Malformed receipt");
                UUID id = UUID.fromString(key);
                receipts.put(id, new Receipt(id, UUID.fromString(parts[0]), parts[1], Double.parseDouble(parts[2]), State.valueOf(parts[3])));
            }
        } catch (IllegalArgumentException error) { throw new IOException("Invalid receipt journal; trading must stay disabled", error); }
    }
    public Receipt get(UUID id) { return receipts.get(id); }
    public long pendingCount() { return receipts.values().stream().filter(r -> r.state() == State.PENDING_PURCHASE || r.state() == State.REDEEMING).count(); }
    public void create(Receipt receipt) throws IOException {
        if (receipt.state() != State.PENDING_PURCHASE || receipts.containsKey(receipt.id())) throw new IllegalStateException("Receipt already exists or invalid initial state");
        Map<UUID, Receipt> next = new HashMap<>(receipts);
        next.put(receipt.id(), receipt);
        persist(next);
    }
    public void transition(UUID id, State expected, State nextState) throws IOException {
        Receipt current = receipts.get(id);
        if (current == null || current.state() != expected) throw new IllegalStateException("Receipt is no longer available");
        boolean allowed;
        switch (expected) {
            case PENDING_PURCHASE: allowed = nextState == State.ACTIVE || nextState == State.VOID; break;
            case ACTIVE: allowed = nextState == State.REDEEMING; break;
            case REDEEMING: allowed = nextState == State.SOLD || nextState == State.ACTIVE; break;
            default: allowed = false;
        }
        if (!allowed) throw new IllegalStateException("Invalid receipt transition");
        Map<UUID, Receipt> next = new HashMap<>(receipts);
        next.put(id, current.withState(nextState));
        persist(next);
    }
    private void persist(Map<UUID, Receipt> next) throws IOException {
        Properties data = new Properties();
        for (Receipt r : next.values()) data.setProperty(r.id().toString(), r.buyer() + "|" + r.mask() + "|" + r.paid() + "|" + r.state());
        StringWriter writer = new StringWriter();
        data.store(writer, "MaskUI purchase receipts - do not edit while the server is running");
        Files.createDirectories(file.getParent());
        Path temporary = Files.createTempFile(file.getParent(), "receipts-", ".tmp");
        try {
            try (FileChannel channel = FileChannel.open(temporary, StandardOpenOption.WRITE)) {
                ByteBuffer bytes = StandardCharsets.UTF_8.encode(writer.toString());
                while (bytes.hasRemaining()) channel.write(bytes);
                channel.force(true);
            }
            Files.move(temporary, file, StandardCopyOption.ATOMIC_MOVE, StandardCopyOption.REPLACE_EXISTING);
            receipts = next;
        } finally { Files.deleteIfExists(temporary); }
    }
}
