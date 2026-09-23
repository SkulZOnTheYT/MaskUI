import com.github.skulzontheyt.maskui.MaskUIPlugin;
import com.github.skulzontheyt.maskui.MaskType;
import org.bukkit.Material;
import org.bukkit.NamespacedKey;
import org.bukkit.entity.Player;
import org.bukkit.event.Event;
import org.bukkit.event.block.Action;
import org.bukkit.event.player.PlayerInteractEvent;
import org.bukkit.inventory.EquipmentSlot;
import org.bukkit.inventory.ItemStack;
import org.bukkit.inventory.PlayerInventory;
import org.bukkit.persistence.PersistentDataType;
import org.bukkit.plugin.java.JavaPlugin;
import java.lang.reflect.Proxy;
import java.util.EnumMap;

/** Run only on an isolated, empty Paper server with MaskUI installed. */
public final class RightClickSmoke extends JavaPlugin {
    @Override public void onEnable() {
        try {
            MaskUIPlugin plugin = (MaskUIPlugin) getServer().getPluginManager().getPlugin("MaskUI");
            for (EquipmentSlot hand : new EquipmentSlot[]{EquipmentSlot.HAND, EquipmentSlot.OFF_HAND}) {
                for (Action action : new Action[]{Action.RIGHT_CLICK_AIR, Action.RIGHT_CLICK_BLOCK}) {
                    test(plugin, hand, action, false, false, true);
                    test(plugin, hand, action, true, false, true);
                    test(plugin, hand, action, false, true, true);
                    test(plugin, hand, action, false, false, false);
                }
            }
            if (!MaskType.DRAGON.effects().stream().anyMatch(effect -> effect.label().equals("Speed III"))) throw new AssertionError("Dragon Speed III missing");
            if (!MaskType.DRAGON.effects().stream().anyMatch(effect -> effect.label().equals("Strength III"))) throw new AssertionError("Strength III missing");
            if (!plugin.getDescription().getVersion().equals("1.0.0")) throw new AssertionError("Version mismatch");
            getLogger().info("MASK_TEST_PASS: 16 right-click cases, Dragon labels and fixed version");
        } catch (Throwable error) {
            getLogger().log(java.util.logging.Level.SEVERE, "MASK_TEST_FAIL", error);
        }
    }

    private void test(MaskUIPlugin plugin, EquipmentSlot hand, Action action, boolean occupied, boolean denied, boolean tagged) {
        var slots = new EnumMap<EquipmentSlot, ItemStack>(EquipmentSlot.class);
        ItemStack mask = new ItemStack(Material.DRAGON_HEAD, 3);
        if (tagged) {
            var meta = mask.getItemMeta();
            meta.getPersistentDataContainer().set(new NamespacedKey(plugin, "mask_type"), PersistentDataType.STRING, "dragon");
            mask.setItemMeta(meta);
        }
        slots.put(hand, mask);
        ItemStack oldHelmet = occupied ? new ItemStack(Material.DIAMOND_HELMET) : null;
        slots.put(EquipmentSlot.HEAD, oldHelmet);
        PlayerInventory inventory = (PlayerInventory) Proxy.newProxyInstance(getClassLoader(), new Class[]{PlayerInventory.class}, (proxy, method, args) -> switch (method.getName()) {
            case "getItem" -> slots.get((EquipmentSlot) args[0]);
            case "setItem" -> { slots.put((EquipmentSlot) args[0], (ItemStack) args[1]); yield null; }
            case "getItemInMainHand" -> slots.get(EquipmentSlot.HAND);
            case "getItemInOffHand" -> slots.get(EquipmentSlot.OFF_HAND);
            case "setItemInMainHand" -> { slots.put(EquipmentSlot.HAND, (ItemStack) args[0]); yield null; }
            case "setItemInOffHand" -> { slots.put(EquipmentSlot.OFF_HAND, (ItemStack) args[0]); yield null; }
            case "getHelmet" -> slots.get(EquipmentSlot.HEAD);
            case "setHelmet" -> { slots.put(EquipmentSlot.HEAD, (ItemStack) args[0]); yield null; }
            case "firstEmpty" -> -1;
            default -> null;
        });
        Player player = (Player) Proxy.newProxyInstance(getClassLoader(), new Class[]{Player.class}, (proxy, method, args) -> switch (method.getName()) {
            case "getInventory" -> inventory;
            case "getGameMode" -> org.bukkit.GameMode.CREATIVE;
            case "getName" -> "MaskTest";
            default -> null;
        });
        PlayerInteractEvent event = new PlayerInteractEvent(player, action, mask, null, org.bukkit.block.BlockFace.UP, hand);
        if (denied) event.setUseItemInHand(Event.Result.DENY);
        plugin.onMaskRightClick(event);
        boolean shouldEquip = tagged && !occupied && !denied;
        if (shouldEquip) {
            if (slots.get(hand).getAmount() != 2 || slots.get(EquipmentSlot.HEAD).getAmount() != 1 || plugin.maskOf(slots.get(EquipmentSlot.HEAD)) != MaskType.DRAGON) throw new AssertionError("Equip/stack conservation failed");
            if (event.useItemInHand() != Event.Result.DENY || event.useInteractedBlock() != Event.Result.DENY) throw new AssertionError("Placement not blocked");
            plugin.onMaskRightClick(event);
            if (slots.get(hand).getAmount() != 2) throw new AssertionError("Repeated event duplicated equip");
        } else if (slots.get(hand).getAmount() != 3 || slots.get(EquipmentSlot.HEAD) != oldHelmet) throw new AssertionError("Protected/ordinary/occupied item changed");
    }
}
