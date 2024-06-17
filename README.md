# Maturitní projekt - Komorník na doma
## Úvod
- Fiktivní webová aplikace pro firmu nabízející služby.

## Téma

- Lákala mě představa vytvořit vlastní e-shop

- Z původního návrhu prodávat mobilní zařízení jsem nabídku upravil na prodej služeb do domácnosti, které jsou často poptávány

- Vznikl proto uživatelsky přívetivý `Komorník na doma`
## Webová stránka
- [www.komorniknadoma.cz](https://www.komorniknadoma.cz)

## Struktura projektu


    ├── app/                    ← adresář s aplikací
    │   ├── Forms/              ← třídy formulářů
    │   │   ├── *FormFactory.php    ← konkrétní třídy formulářů
    │   │   └── FormValidator.php   ← třída obsahující validační funkce
    │   ├── Presenters/         ← třídy presenterů
    │   │   ├── templates/      ← šablony
    │   │   └── *Presenter.php  ← Presentery
    │   ├── Models/             ← adresář s modely pracující s databází
    │   │    └── *Facade.php     ← konkrétní třídy modelů
    │   ├── Utils/              ← adresář Utils
    │   │    └── Authenticator.php   ← vlastní autentikátor
    │   ├── Router/             ← konfigurace URL adres
    │   └── Bootstrap.php       ← zaváděcí třída Bootstrap
    ├── bin/                    ← skripty spouštěné z příkazové řádky
    ├── config/                 ← konfigurační soubory
    ├── log/                    ← logování chyb
    ├── temp/                   ← dočasné soubory, cache, …
    ├── vendor/                 ← knihovny instalované Composerem
    │   └── autoload.php        ← autoloading všech nainstalovaných balíčků
    ├── www/                    ← veřejný adresář - jediný přístupný z prohlížeče
    │     └── index.php         ← prvotní soubor, kterým se aplikace spouští
    └── init.sql                ← SQL script pro vytvoření databáze (včetně demodat)
