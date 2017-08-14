# ygg rss feed generator

YGG rss feed generator est un script php permettant de générer des flux rss des derniers torrents de YGGtorrent.
Le script est basé sur YGG Crawler (https://github.com/atogeek/ygg_crawler), un peu modifié, créé par atogeek !

## Installation
Cloner le repo dans un répertoire, assurez vous que les bonnes permissions et les bons groupes soit appliqués.
Ouvrez Ygg.php avec votre éditeur préféré et modifiez les lignes 93 et 94 pour renseigner votre couple login/pass

    $this->login = 'login';
    $this->password = 'pass';

## Utilisation
On peut générer un flux d'une catégorie ou d'une sous catégorie.
Les noms des catégories et sous catégories sont les mêmes que sur le site mais en minuscule, sans espace ni caractères spéciaux.

exemple:
- RSS Film: https://maseedbox.com/ygg/rss.php?category=filmvideo&subcategory=film
- RSS Serie TV: https://maseedbox.com/ygg/rss.php?category=filmvideo&subcategory=serietv

#### Paye moi une bière !
Si ce que j'ai fait te plait et que ça t'es utile, paye moi une bière ;)
- Paypal : https://paypal.me/guiisch
- ETH: 0xbE08c367280EF1b945c327419Afec474B5E1eff6
