<p align="right">
  <a href="https://poggit.pmmp.io/p/PowerEssentials"><img src="https://poggit.pmmp.io/shield.state/PowerEssentials" height="20px"></a>
  <img src="https://raw.githubusercontent.com/angga7togk/PowerEssentials/refs/heads/main/img/indonesia.png" height="23px">
</p>

<p align="center">
  <a href="https://github.com/PowerEssentials">
    <img src="https://raw.githubusercontent.com/angga7togk/PowerEssentials/refs/heads/main/icon.png" width="25%">
  </a>
</p>

<h1 align="center">PowerEssentials</h1>
<p align="center"><strong>The ultimate Pocketmine-MP plugin to streamline server management and enhance gameplay.</strong></p>

---

## ✨ Features

- **Custom language support**: Localize your server effortlessly.
- **Full Configuration**: Customize the plugin's behavior.
- **Command control**: Enable or disable commands as needed.
- **Gamemode on join**: Automatically set players' gamemode.
- **Vanilla coordinates**: Display precise coordinates for players.
- **Anti-namespace protection**: Prevent unauthorized namespace usage.
- **One Player Sleep**: Allow only one player to sleep in a world.

---

## 📜 Commands

| Command                                                              | Description                                                                        | Status |
| -------------------------------------------------------------------- | ---------------------------------------------------------------------------------- | ------ |
| `/hub`, `/lobby`, `/setlobby`                                        | Set and teleport to the lobby.                                                     | ✅     |
| `/gmc [player]`, `/gms [player]`, `/gmspc [player]`, `/gma [player]` | Change gamemode for yourself or others.                                            | ✅     |
| `/fly [player]`                                                      | Enable or disable flying for yourself or others.                                   | ✅     |
| `/nickname <nickname/reset> [player]`                                | Change your or another player's nickname.                                          | ✅     |
| `/banitem [world]`, `/unbanitem [world]`, `/banitemlist [world]`     | Ban specific items in a world.                                                     | ✅     |
| `/tpa <to,here,accept,deny,cancel> <player>`                         | Request to teleport to another player.                                             | ✅     |
| `/rtp`, `/randomteleport`                                            | Randomly teleport to a world (Anti Water Area).                                    | ✅     |
| `/home [name]`, `/sethome <name>`, `/delhome <name>`                 | Manage homes for players.                                                          | ✅     |
| `/warp [name]`, `/addwarp <name>`, `/delwarp <name>`                 | Create and teleport to warps.                                                      | ✅     |
| `/heal [name]`, `/feed [name]`                                       | Heal or feed yourself or another player.                                           | ✅     |
| `/vanish [player]`, `/vanishlist`                                    | Toggle vanish mode.                                                                | ✅     |
| `/sudo <player> <message/command>`                                   | Execute a command or send a message as another player.                             | ✅     |
| `/size <size>`                                                       | Change player size.                                                                | ✅     |
| `/afk`                                                               | Mark yourself as AFK.                                                              | ✅     |
| `/coordinates`                                                       | Display current coordinates.                                                       | ✅     |
| `/bless [player]`                                                    | Clear bad effects.                                                                 | ✅     |
| `/repair <hand,all> [player]`                                        | Repair items.                                                                      | ✅     |
| `/itemid`                                                            | View the ID of the item in hand.                                                   | ✅     |
| `/getpos <player>`                                                   | Get the position of a player.                                                      | ✅     |
| `/senditem <player> [amount]`                                        | Transfer an item to another player.                                                | ✅     |
| `/kickall [reason]`                                                  | Kick all players with an optional reason.                                          | ✅     |
| `/tpall [player]`                                                    | Teleport all players to a target player.                                           | ✅     |
| `/worldprotect <type> <value> [world]`                               | Protect worlds with various settings (place, PvP, hunger, etc.).                   | ✅     |
| `/mute <player> <time: 10m> [reason]`, `/unmute [player]`                                         | Mute Player.                                                                       | ✅     |
| `/tempban <player> <time: 10m> [reason]`                             | Banned players with time.                                                          | ⌛     |
| `/bancommand <command> [world]`                                      | Banned command on specific world.                                                  | ⌛     |
| `/lightning [player]`                                                | Send lightning.                                                                    | ⌛     |
| `/staffchat`                                                         | Entering staff chat mode.                                                          | ⌛     |
| `/walkspeed <speed: 1.5>`                                            | Walkspeed.                                                                         | ⌛     |
| `/flyspeed <speed: 1.5>`                                             | Flyspeed.                                                                          | ⌛     |
| `/renameitem <name>`                                                 | Rename the item on your hand.                                                      | ⌛     |
| `/itemlore <name>`                                                   | Set lore the item on your hand, (`use {line} to create new line`).                 | ⌛     |
| `/enchants <enchant:level>[]`                                        | enchant lots of items in your hand, (`/enchants unbreaking:2 thorns:1 vampire:1`). | ⌛     |
| `/pvptoggle`                                                         | On/Off your pvp mode.                                                              | ⌛     |
| `/antitp`                                                            | Anti Teleport.                                                                     | ⌛     |
| `/nightvision`                                                       | Night Vision mode.                                                                 | ⌛     |

---

## 📂 Installation

1. Download the latest release from the [releases page](https://github.com/angga7togk/PowerEssentials/releases).
2. Place the `PowerEssentials.phar` file into your server's `plugins` folder.
3. Restart your server.

---

## 🌐 Localization

PowerEssentials supports multiple languages. Contributions for new translations are welcome!

---

## 🛠️ Contribution

We welcome contributions! Feel free to open issues or submit pull requests to help improve PowerEssentials.

---

## 🔗 Links

- [GitHub Repository](https://github.com/angga7togk/PowerEssentials)

---

## 📜 Credits

Icon by [Flaticon](https://www.flaticon.com/)

---

## 📜 License

PowerEssentials is licensed under the [MIT License](LICENSE).
