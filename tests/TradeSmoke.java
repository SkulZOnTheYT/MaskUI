import com.github.skulzontheyt.maskui.*;
import net.milkbowl.vault.economy.*;
import org.bukkit.Material;
import org.bukkit.entity.Player;
import org.bukkit.inventory.ItemStack;
import org.bukkit.inventory.PlayerInventory;
import org.bukkit.plugin.java.JavaPlugin;
import java.lang.reflect.*;
import java.nio.file.*;
import java.util.*;

/** Isolated Paper test plugin. Uses fake economy/player objects; no real accounts. */
public final class TradeSmoke extends JavaPlugin {
    private MaskUIPlugin plugin;
    private double balance = 100000;
    private int deposits;
    private boolean failDeposit, failWithdraw, throwDeposit;
    private Path journal;
    private int assertions;
    @Override public void onEnable() {
        plugin = (MaskUIPlugin) getServer().getPluginManager().getPlugin("MaskUI");
        Object oldEconomy = null, oldReceipts = null, oldReady = null;
        double oldPrice = plugin.price(MaskType.SKELETON);
        Object oldPercent = plugin.getConfig().get("selling.refund-percent");
        Object oldEnabled = plugin.getConfig().get("selling.enabled");
        try {
            oldEconomy = get("economy"); oldReceipts = get("receipts"); oldReady = get("tradingReady");
            journal = Files.createTempDirectory("mask-trade-smoke-").resolve("receipts.properties");
            set("receipts", new ReceiptStore(journal)); set("tradingReady", true);
            plugin.getConfig().set("selling.enabled", true);
            plugin.getConfig().set("selling.refund-percent", 50);
            plugin.getConfig().set("masks.skeleton.price", 5000);
            Economy fake = (Economy) Proxy.newProxyInstance(getClassLoader(), new Class[]{Economy.class}, (proxy, method, args) -> {
                return switch (method.getName()) {
                    case "getBalance" -> balance;
                    case "has" -> balance >= (double) args[1];
                    case "withdrawPlayer" -> {
                        double amount = (double) args[1];
                        if (!failWithdraw) balance -= amount;
                        yield response(amount, failWithdraw);
                    }
                    case "depositPlayer" -> {
                        if (throwDeposit) throw new IllegalStateException("Expected test: unknown provider outcome");
                        double amount = (double) args[1];
                        if (!failDeposit) { balance += amount; deposits++; }
                        yield response(amount, failDeposit);
                    }
                    default -> null;
                };
            });
            set("economy", fake);
            Buyer buyer = new Buyer();
            buy(buyer); check(balance == 95000 && buyer.items[0] != null, "purchase withdraws and delivers");
            var offer = plugin.sellOffer(buyer.player, MaskType.SKELETON);
            check(offer != null && offer.price() == 2500, "refund based on paid price");
            ItemStack duplicate = buyer.items[0].clone();
            Buyer other = new Buyer(); other.items[0] = duplicate.clone();
            check(plugin.sellOffer(other.player, MaskType.SKELETON) != null, "holder of purchased item accepted");
            Buyer legacy = new Buyer();
            Method create = MaskUIPlugin.class.getDeclaredMethod("createMask", Player.class, MaskType.class); create.setAccessible(true);
            legacy.items[0] = (ItemStack) create.invoke(plugin, legacy.player, MaskType.SKELETON);
            check(plugin.sellOffer(legacy.player, MaskType.SKELETON) == null, "admin/legacy item rejected");
            legacy.items[1] = new ItemStack(Material.SKELETON_SKULL);
            check(plugin.sellOffer(legacy.player, MaskType.SKELETON) == null, "ordinary head rejected");
            buyer.items[0] = null;
            sell(buyer, offer); check(deposits == 0, "missing item after confirmation rejected");
            buyer.items[39] = duplicate.clone();
            check(plugin.sellOffer(buyer.player, MaskType.SKELETON) == null, "equipped mask excluded");
            buyer.items[39] = null; buyer.items[40] = duplicate.clone();
            check(plugin.sellOffer(buyer.player, MaskType.SKELETON) != null, "off-hand accepted");
            plugin.getConfig().set("selling.refund-percent", 25);
            sell(buyer, offer); check(deposits == 0 && buyer.items[40] != null, "stale refund rejected");
            plugin.getConfig().set("selling.refund-percent", 50);
            plugin.getConfig().set("masks.skeleton.price", 500000);
            check(plugin.sellOffer(buyer.player, MaskType.SKELETON).price() == 2500, "shop price increase does not inflate refund");
            set("receipts", new ReceiptStore(journal));
            failDeposit = true; sell(buyer, offer);
            check(deposits == 0 && buyer.items[40] != null && plugin.sellOffer(buyer.player, MaskType.SKELETON) != null, "failed deposit restores item and receipt");
            failDeposit = false;
            var confirm = MaskMenu.sellConfirmation(plugin, buyer.player, offer);
            check(confirm.offer().receipt().equals(offer.receipt()) && confirm.getInventory().getItem(30).getType() == Material.LIME_CONCRETE, "sale confirmation binds receipt");
            var shop = new MaskMenu(plugin, buyer.player, MaskMenu.Kind.SHOP);
            check(shop.getInventory().getItem(4).getItemMeta().getDisplayName().contains("95,000"), "main menu shows current balance");
            sell(buyer, offer); check(deposits == 1 && balance == 97500 && buyer.items[40] == null, "successful sale consumes item and pays once");
            buyer.items[0] = duplicate.clone();
            set("receipts", new ReceiptStore(journal));
            sell(buyer, offer); check(deposits == 1 && plugin.sellOffer(buyer.player, MaskType.SKELETON) == null, "duplicate receipt rejected after restart");
            buyer.items[0] = null;
            plugin.getConfig().set("masks.skeleton.price", 5000);
            failWithdraw = true; buy(buyer);
            check(buyer.items[0] == null && balance == 97500, "failed withdrawal delivers no item");
            failWithdraw = false; buy(buyer);
            var second = plugin.sellOffer(buyer.player, MaskType.SKELETON);
            check(second != null && !second.receipt().equals(offer.receipt()), "new purchase gets a new receipt");
            plugin.getConfig().set("selling.enabled", false);
            sell(buyer, second); check(buyer.items[0] != null && deposits == 1, "disabled selling blocks stale confirmation");
            plugin.getConfig().set("selling.enabled", true);
            plugin.getConfig().set("selling.refund-percent", 101);
            check(plugin.sellOffer(buyer.player, MaskType.SKELETON) == null, "invalid refund disabled");
            plugin.getConfig().set("selling.refund-percent", 50);
            throwDeposit = true; sell(buyer, second);
            check(!(boolean)get("tradingReady"), "unknown economy result pauses trading");
            check(new ReceiptStore(journal).get(second.receipt()).state() == ReceiptStore.State.REDEEMING, "uncertain sale remains blocked across restart");
            getLogger().info("TRADE_TEST_PASS: " + assertions + " assertions covering buy/sell, replay, restart, identity, rollback, UI and uncertain payment");
        } catch (Throwable error) { getLogger().log(java.util.logging.Level.SEVERE, "TRADE_TEST_FAIL", error); }
        finally {
            try { set("economy", oldEconomy); set("receipts", oldReceipts); set("tradingReady", oldReady); }
            catch (Exception error) { throw new RuntimeException(error); }
            plugin.getConfig().set("masks.skeleton.price", oldPrice);
            plugin.getConfig().set("selling.refund-percent", oldPercent);
            plugin.getConfig().set("selling.enabled", oldEnabled);
        }
    }
    private EconomyResponse response(double amount, boolean failure) {
        return new EconomyResponse(amount, balance, failure ? EconomyResponse.ResponseType.FAILURE : EconomyResponse.ResponseType.SUCCESS, failure ? "Expected test failure" : null);
    }
    private Object get(String name) throws Exception { Field f = MaskUIPlugin.class.getDeclaredField(name); f.setAccessible(true); return f.get(plugin); }
    private void set(String name, Object value) throws Exception { Field f = MaskUIPlugin.class.getDeclaredField(name); f.setAccessible(true); f.set(plugin, value); }
    private void buy(Buyer buyer) throws Exception { Method m = MaskUIPlugin.class.getDeclaredMethod("purchase", Player.class, MaskType.class, double.class); m.setAccessible(true); m.invoke(plugin, buyer.player, MaskType.SKELETON, 5000); }
    private void sell(Buyer buyer, MaskUIPlugin.SellOffer offer) throws Exception { Method m = MaskUIPlugin.class.getDeclaredMethod("sell", Player.class, MaskUIPlugin.SellOffer.class); m.setAccessible(true); m.invoke(plugin, buyer.player, offer); }
    private void check(boolean condition, String label) { if (!condition) throw new AssertionError(label); assertions++; }
    private final class Buyer {
        final UUID id = UUID.randomUUID();
        final ItemStack[] items = new ItemStack[41];
        final PlayerInventory inventory = (PlayerInventory) Proxy.newProxyInstance(getClassLoader(), new Class[]{PlayerInventory.class}, (proxy, method, args) -> switch (method.getName()) {
            case "getItem" -> items[(int)args[0]];
            case "setItem" -> { items[(int)args[0]] = (ItemStack)args[1]; yield null; }
            case "getHelmet" -> items[39];
            case "firstEmpty" -> { int slot = -1; for (int i = 0; i < 36; i++) if (items[i] == null) { slot = i; break; } yield slot; }
            default -> null;
        });
        final Player player = (Player) Proxy.newProxyInstance(getClassLoader(), new Class[]{Player.class}, (proxy, method, args) -> switch (method.getName()) {
            case "getInventory" -> inventory;
            case "getUniqueId" -> id;
            case "getName" -> "TradeTest";
            default -> null;
        });
    }
}
