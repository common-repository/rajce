=== rajce ===
Contributors: vEnCa-X
Donate link: http://www.venca-x.cz
Tags: Rajče, fotogalerie
Requires at least: 4.0
Tested up to: 6.0
Stable tag: /trunk/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin pro zobrazení naposledy přidaných galerií na http://www.rajce.idnes.cz/

== Description ==

Plugin se nastavuje jako widget. Je nutné zadat URL galerie (umístěné na rajce.idnes.cz) a počet posledních alb které budou zobrazeny.

== Installation ==

1) nainstalujte plugin rajce (http://wordpress.org/plugins/rajce/)
2) v administraci vyberte z menu: Plugins -> Installed Plugins
3) v sekci Installed Plugins aktivujte plugin rajce
4) v sekci Administrace -> Widgets nastavte plugin Rajce do vybraného Sidebaru
5) u pluginu Rajce zadejte Adresa galerie (http://sdh-zabori.rajce.idnes.cz/) a vyberte jaké informace chcete zobrazovat

=== Zobrazení naposledy přidaných alb - widget ===
K tomuto účelu slouží widget. V menu: Appearance->Widgets se nastavuje zobrazení widgetů. Pro nás je nejdůležitější widget s názvem *Rajče - seznam galerii*, přesuňte ho na požadované místo.
Tomuto wigetu nastavte následující parametry:
Titulek widgetu: Titulek bloku ve kterém se zobrazí naposledy přidané galerie
URL adresa galerie: URL adresa prosilu kde jsou fotky. Příklad: http://sdh-zabori.rajce.idnes.cz/
Počet alb: Číslo určující kolik naposledy přidaných galerií bude zobrazeno
Zobraz datum: Má se u galerie zobrazovat datum přidání?
Skrýt název serveru: Server se vždy nějak jmenu (u všech galerií je to stjené jméno) - má se zobrazovat. Doporučuji nastavit na ANO
Otevřít v novém okně: Má se detail galerie zobrazovat v novém okně?

=== Zobrazení galerie v příspěvku ===
Je to jednoduché. Do příspěvku vložíte tento kód: [rajce url="http://sdh-zabori.rajce.idnes.cz/Stedry_den_v_hospode_20.12.2015/"]
Nemusím připomínat, že funguje pouze na galerie od rajce :-)

=== Zobrazení fotky v příspěvku ===
Máme dvě možnosti jak fotku v příspěvku zobrazit. Buď jako malý náhled fotky - po kliknutí na fotku se zvětší:
[rajce url="http://sdh-zabori.rajce.idnes.cz/Stedry_den_v_hospode_20.12.2015#DSC_6315-Edit.jpg"]

Nebo jako velkou fotku - nataženou na 100% šířku stránky - po kliknutí na fotku se zvětší:
[rajce url="http://sdh-zabori.rajce.idnes.cz/Stedry_den_v_hospode_20.12.2015#DSC_6315-Edit.jpg" size="big"]

=== Ukázka kódu pro zobrazení fotek v příspěvku ===

        <h1>Zobrazení galerie</h1>
        [rajce url="http://sdh-zabori.rajce.idnes.cz/Stedry_den_v_hospode_20.12.2015/"]

        <h1>Detail velké fotky</h1>
        [rajce url="http://sdh-zabori.rajce.idnes.cz/Stedry_den_v_hospode_20.12.2015#DSC_6315-Edit.jpg" size="big"]

        <h1>Malé fotka:</h1>
        [rajce url="http://sdh-zabori.rajce.idnes.cz/Stedry_den_v_hospode_20.12.2015#DSC_6315-Edit.jpg"]
        [rajce url="http://sdh-zabori.rajce.idnes.cz/Stedry_den_v_hospode_20.12.2015#DSC_6243.jpg"]
        [rajce url="http://sdh-zabori.rajce.idnes.cz/Stedry_den_v_hospode_20.12.2015#DSC_6246.jpg"]

== Frequently asked questions ==
Jakou adresu mám vyplnit do pole Adresa galerie?
Do tohoto pole můžete zadat pouze galerii umístěnou na serveru rajce. Např: http://sdh-zabori.rajce.idnes.cz/




== Screenshots ==

1. Vizuální podoba
2. Administrace (widget)
3. Ukázka možností zobrazení fotografií

== Changelog ==

= 0.1 =
* First version

= 0.2 =
* Fix saving widget

= 0.3 =
* Přidáno zobrazování galerirí a jednotlivých fotek v příspěvcích

- 0.4.0 -
* Fix in PHP 8.0

- 0.4.2 -
* Tested up to: 6.0

== Upgrade notice ==

First version


