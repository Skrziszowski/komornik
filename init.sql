CREATE DATABASE IF NOT EXISTS Komornik;
use Komornik;

CREATE TABLE IF NOT EXISTS roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(255) NOT NULL
);

CREATE TABLE  IF NOT EXISTS visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT, 
    name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL,
    gender VARCHAR(2),                      
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,         
    phone VARCHAR(18),                      
    dayB VARCHAR(255),                      
    address VARCHAR(255),                 
    city VARCHAR(255),
    role_id INT NOT NULL DEFAULT 2, 
    FOREIGN KEY (role_id) REFERENCES roles(role_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS services(
    service_id INT PRIMARY KEY AUTO_INCREMENT,
    heading_card TEXT NOT NULL,
    image_source_card VARCHAR(255) NOT NULL,
    info_page TEXT NOT NULL,
    image_source_page VARCHAR(255) NOT NULL,
    cards TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS orders(
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL,
    service_id INT NOT NULL,
    FOREIGN KEY (service_id) REFERENCES services(service_id),
    price INT(5) NOT NULL,
    type_service VARCHAR(100),
    address varchar(255) DEFAULT NULL,
    city varchar(255) DEFAULT NULL,
    orderDate DATE,
    orderTime TIME,
    arriveDate DATETIME,
    note TEXT,
    order_status INT NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS career(
    career_id INT PRIMARY KEY AUTO_INCREMENT,
    date_arrive DATETIME,
    name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(2) NOT NULL,
    phone VARCHAR(18) NOT NULL,
    email VARCHAR(255) NOT NULL,
    education INT NOT NULL,
    position TEXT NOT NULL,
    hobbies TEXT NOT NULL,
    why TEXT NOT NULL,
    skills TEXT NOT NULL,
    career_status INT NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS complaint(   
    complaint_id INT PRIMARY KEY AUTO_INCREMENT,
    date_arrive DATETIME,
    email VARCHAR(255) NOT NULL,
    theme TEXT NOT NULL,
    mainText TEXT NOT NULL
);

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'admin'),
(2, 'customer');

INSERT INTO `services` (`service_id`, `heading_card`, `image_source_card`, `info_page`, `image_source_page`, `cards`) VALUES
(1, 'Úklidové služby', 'images/icons/clean.png', 'Potřebujete uklidit byt či dům? Není problém. Naši zkušení úklidový pracovníci jsou pečlivě školeni a vybaveni nejmodernějším vybavením pro úklid vašeho prostoru. Dbáme na každičký detail, aby vaše prostory vypadaly skvostně. Jsme také zavázáni k ochraně životního prostředí a přizpůsobíme se vašim potřebám a harmonogramu. Naši pracovníci procházejí důkladnými kontrolami a jsou plně pojištěni, abyste měli klidnou mysl.', 'images/imagesInfo/clean.jpg', 'Základní,800,Úklid všech podlah,Vytírání a vysávání,Čištění a dezinfekce,Stírání prachu,Vynesení odpadků|\r\nPremium,1500,Služby Základního balíčku,Umývání nádobí,Čištění oken (vnitřní strana),Praní a žehlení prádla,Čištění a dezinfekce kuchyně včetně spotřebičů|\r\nExpress,2500,Všechny služby Premium balíčku,Čištění a dezinfekce koberců,Mytí oken (vnitřní i vnější strana),Detailní čištění a dezinfekce všech koupelen,Praní, Žehlení a skládání prádla (včetně ložního prádla)'),
(2, 'Zahradnické služby', 'images/icons/lawn-mower.png', 'Zahradnické služby jsou klíčovým prvkem pro udržení a zlepšení krásy a funkčnosti zahrad a venkovních prostorů. Naši profesionální zahradníci mají bohaté znalosti o různých druzích rostlin, půdních podmínkách a vhodných zahradnických postupech. Nabízejí rozmanité služby, jako je základní údržba, zahrnující sekání trávy a zalévání, ale také zahradní plánování a zakládání nových zahrad. Zahradnické služby mohou být pro majitele domů i komerčních nemovitostí neocenitelné, neboť pomáhají udržovat atraktivní a zdravé venkovní prostory, které přispívají k celkové kvalitě života a hodnotě nemovitosti.', 'images/imagesInfo/garden.jpg', 'Základní,800,Sekání trávníku,Základní udržování zahrady,Odstranění plevelu,Krácení keřů,Odvoz zahradního odpadu|\r\nPremium,1500,Služby Základního balíčku,Výstavba nových květin a keřů a stromů,Mulčování a hnojení zahrady,Instalace zahradního osvětlení|\r\nExpress,2500,Všechny služby Premium balíčku,Návrh a výstavba vodního prvku (jezírko či vodopád),Instalace venkovního grilu,Péče o zahrady s exotickými rostlinami'),
(3, 'Dovoz jídla', 'images/icons/delivery.png', 'Přemýšleli jste nad tím, že by vám někdo každý den vozil nákup dle vaší potřeby? My jsme tento sen zrealizovali. Každý den pro vás vozíme nákupy stovkám lidí. Každý den tedy máte k snídani čerstvé pečivo, v ledničce nákup dle vašeho seznamu. Místo chození na nákupy a čekání ve frontách se můžete věnovat důležitějším věcem. Vaše místo v ledničce už nikdy nebude přeplněné, každý den vám předáme nákup na den a další den dostanete zase jídlo jiné. Chcete nakoupit k snídani čerstvé pečivo, k obědu nějakou italskou kuchyni a k večeři nakládané maso s bramborami? O vše se postaráme a dle času, který určíte vám nákup s úsměvem předáme.', 'images/imagesInfo/food.jpg', 'Jednorázový,200,Jednorázový dovoz nákupu či jídla až k vám domů dle přání'),
(4, 'Péče o mazlíčka', 'images/icons/pets.png', 'Chcete odjet na dovolenou a musíte nechat vašeho milovaného mazlíčka doma? I na to myslíme. Naši ochotní zaměstnanci se s úsměvem na tváři o vaši ratolest rádi postarají. Potřebujete mazlíčka vyvenčit? Naši zaměstnanci se o to postarají. Odjíždíte na delší dobu a bude potřeba se o mazlíčka starat déle? Není problém. Nabízíme služby jako koupání vašeho mazlíčka, stříhání a jiné doplňkové služby. Každý den vám dáme krátkou a výstižnou zprávu o tom, jak se váš mazlíček má. Není tedy důvod se čehokoliv obávat. Ceny jsou uvedeny za jeden den.', 'images/imagesInfo/pets.jpg', 'Základní,300,Venčení vašeho mazlíčka (3krát denně)|\r\nPremium,500,Venčení mazlíčka,Koupání|\r\nExpress,600, Venčení mazlíčka,Koupání,Stříhání'),
(5, 'Stěhování nábytku', 'images/icons/furniture.png', 'Stěhování nábytku je často emocionálně náročným procesem, který může být spojen s velkým přesunem nebo změnou v životě. Bez ohledu na důvod stěhování, je klíčové, aby to probíhalo hladce a efektivně. Profesionální služby stěhování nábytku vám mohou ulehčit tento proces, usnadnit práci a zajistit, že vaše cenné věci budou bezpečně přesunuty na nové místo. Máme zkušenosti a profesionály z oboru. Máme za sebou desítky odstěhovaných továren či hal. Stovky rodin si naše služby vychvalují. Nabízíme také stěhování cenných sbírek či drahých obrazů. Cílem je, aby náš zákazník neměl žádné starosti a vše zařídíme my.', 'images/imagesInfo/furniture.jpg', 'Základní,900,Přeprava nábytku,Montáž a demontáž nábytku,Ochrana nábytku balícím materiálem,Doprava s menší dodávkou nebo přepravním autem|\r\nPremium,1600,Všechny služby Základního balíčku,Dočasné skladování věcí na dobu stěhování,Úklid po dokončení stěhování, Připojení a odpojení spotřebičů (pračky a ledničky),Zajištění stěhovacího materiálu (krabice a bublinková folie)|\r\nExpress,2500,Všechny služby Premium balíčku,Stěhování cenných obrazů nebo umění,Speciální péče o křehký nebo cenný nábytek,Profesionální tým stěhováků s rozšířenými dovednostmi'),
(6, 'Servis auta', 'images/icons/car.png', 'Servis auta je nepostradatelná služba pro každého majitele vozidla, bez ohledu na jeho značku či typ. Tato profesionální služba zajišťuje pravidelnou údržbu a opravy vozidel, což přispívá k bezpečnosti na silnicích a prodlužuje životnost automobilu. V servisu auta se odborníci specializují na různé aspekty, včetně pravidelných olejových výměn, kontrolních prohlídek, oprav motorů, brzdových systémů a mnoho dalšího. Kvalitní servis vozidel využívá nejen kvalifikovaný personál, ale také moderní technologie a vysoce kvalitní náhradní díly. To zajišťuje, že vozidlo bude fungovat spolehlivě a efektivně. Služba servisu auta také pomáhá majitelům udržet výkon a hodnotu svých vozidel.', 'images/imagesInfo/car.jpg', 'Základní,1000,Kompletní kontrola motoru a výměna oleje s vysoko kvalitním syntetickým olejem,Kontrola brzdového systému a případná výměna brzdových destiček,Kontrola tlaku pneumatik a doplnění vzduchu,Umytí auta|\r\nPremium,1900,Všechny služby Základního balíčku,Základní kontrola osvětlení,Kompletní kontrola motoru a výměna oleje s vysokokvalitním syntetickým olejem,Čištění a výměna vzduchového filtru kabiny,Detailní vyčištění interiéru|\r\nExpress,3500,Všechny služby Premium balíčku,Kompletní rozbor motoru včetně výměny oleje a filtrů a svíček,Diagnostika a servis klimatizace a topení,Komplexní kontrola podvozku a tlumičů a případné výměny tlumičů,Diagnostika výfukového systému a výměna potřebných částí');

INSERT INTO `users` (`user_id`, `name`, `surname`, `gender`, `email`, `password`, `phone`, `dayB`, `address`, `city`, `role_id`) VALUES
(1, '', '', '', 'admin', '$2y$10$btpQP0bzcAltiCHXFUM7UOonouoRYkNxr8zz.FQI0tSE1CHWCSRlm', '', '', '', '', 1);
