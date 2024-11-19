/**
 * @package GeoIP Redirecter
 */
/*
Plugin Name: GeoIP Redirecter
Plugin URI: http://www.waterwhite.sk
Description: Custom redirection of webpage for visitors from other countries to other url. Shows simple strip block on top of page with notification. Works with timeout setting to let visitor see content of original site for a while. Visitor can decide to stay or be redirected.
Version: 1.2.0
Author: waterwhite
Author URI: http://www.waterwhite.sk
License: GPL2
Text Domain: geoip-redirecter
*/

 
ENGLISH:
-------------
Basic redirecting of site using homepage redirection.
Shows simple strip block on top of page with notification.
Works with timeout setting to let visitor see whole content for a while.
Uses hard redirecting, without option to stay on this site.

You can:
- filter which countries to redirect - one or more (coma separated)
- set up one common redirection url
- set up timeout - how long will visitor stay on page before redirection, or set zero (0) for no automatic redirection
- add your custom notification text, nw url link to redirect will be added automatically to end of line.
- add IP address, one or more (coma separated) to exclude from redirecting
- set where will plugin be active: homepage only or on each page of website
- manual redirect - New line with "yes/no" question will appear in strip block, if this field is filled.

This plugin uses external Geo IP database from http://www.geoplugin.net to identify country.


SLOVENSKY:
-------------
- pokiaľ je aj aktivovaný ako plugin, ale nemá nastavenia, tak nereaguje a nič sa nezobrazuje
- ak sa nastaví niektorá krajina, tak po detekcii sa oznam zobrazí len v nastavenej krajine (dá sa aj viac naraz)
- countdown sa dá vypnúť nastavením na 0, a oznam ostane pasívne stále zobrazený, nikam nepresmeruje, len na klik
- text aj url sa dá ľubovoľne meniť
- detekciu krajiny som spravil podľa Geo IP kde je momentálne návštevník pripojený, nie podľa jazyka prehliadača, ani zvoleného na webstránke, pretože tak bola požiadavka: "zákazník, ktorý príde z HK, TW, CN, MO"


------------------------------------------------

### Screeny

#### Zobrazenie na stránke:
![Appearance on page](/docs/screen_frontpage.jpg "Appearance on page")

#### Nastavenia modulu:
![Backoffice module settings](/docs/screen_settings.jpg "Module settings")