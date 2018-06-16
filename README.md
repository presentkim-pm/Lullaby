[![Telegram](https://img.shields.io/badge/Telegram-PresentKim-blue.svg?logo=telegram)](https://t.me/PresentKim)
<img src="./assets/icon/index.svg" height="256" width="256">  

[![License](https://img.shields.io/github/license/PMMPPlugin/Lullaby.svg?label=License)](LICENSE)
[![Release](https://img.shields.io/github/release/PMMPPlugin/Lullaby.svg?label=Release)](https://github.com/PMMPPlugin/Lullaby/releases/latest)
[![Download](https://img.shields.io/github/downloads/PMMPPlugin/Lullaby/total.svg?label=Download)](https://github.com/PMMPPlugin/Lullaby/releases/latest)


A plugin healing when a player is lying in bed for PocketMine-MP

## Command
Main command : `/lullaby <heal | delay | lang | reload | save>`

| subcommand | arguments           | description            |
| ---------- | ------------------- | ---------------------- |
| Heal       | \<amount\>          | Set heal amount        |
| Delay      | \<tick\>            | Set heal delay         |
| Lang       | \<language prefix\> | Load default lang file |
| Reload     |                     | Reload all data        |
| Save       |                     | Save all data          |




## Permission
| permission         | default | description       |
| ------------------ | ------- | ----------------- |
| lullaby.cmd        | OP      | main command      |
|                    |         |                   |
| lullaby.cmd.heal   | OP      | heal  subcommand  |
| lullaby.cmd.delay  | OP      | delay subcommand  |
| lullaby.cmd.lang   | OP      | lang subcommand   |
| lullaby.cmd.reload | OP      | reload subcommand |
| lullaby.cmd.save   | OP      | save subcommand   |