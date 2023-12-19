BEGIN
  EXECUTE IMMEDIATE 'DROP TABLE Material';
  EXECUTE IMMEDIATE 'DROP TABLE Consumable';
  EXECUTE IMMEDIATE 'DROP TABLE AscensionMaterial';
  EXECUTE IMMEDIATE 'DROP TABLE Region';
  EXECUTE IMMEDIATE 'DROP TABLE Subregion';
  EXECUTE IMMEDIATE 'DROP TABLE Weapon';
  EXECUTE IMMEDIATE 'DROP TABLE ArtifactSet';
  EXECUTE IMMEDIATE 'DROP TABLE Artifact';
  EXECUTE IMMEDIATE 'DROP TABLE Enemies';
  EXECUTE IMMEDIATE 'DROP TABLE Boss';
  EXECUTE IMMEDIATE 'DROP TABLE CHARACTER';
  EXECUTE IMMEDIATE 'DROP TABLE BossDrops';
  EXECUTE IMMEDIATE 'DROP TABLE EnemyDrops';
  EXECUTE IMMEDIATE 'DROP TABLE CharacterCanWield';
  EXECUTE IMMEDIATE 'DROP TABLE CharacterArtifacts';
  EXECUTE IMMEDIATE 'DROP TABLE RequiredMaterialForWeapon';
  EXECUTE IMMEDIATE 'DROP TABLE MaterialsFoundAt';
  EXECUTE IMMEDIATE 'DROP TABLE CharacterInteractions';
  EXECUTE IMMEDIATE 'DROP TABLE BattleInRegion';
  EXECUTE IMMEDIATE 'DROP TABLE ConsumableBoosts';
  EXECUTE IMMEDIATE 'DROP TABLE RequiredMaterialForCharacter';
  EXECUTE IMMEDIATE 'DROP TABLE Enhances';
EXCEPTION
  WHEN OTHERS THEN
    NULL;
END;

/* delete tables
BEGIN

  FOR i IN (SELECT ut.table_name
              FROM USER_TABLES ut) LOOP
    EXECUTE IMMEDIATE 'drop table '|| i.table_name ||' CASCADE CONSTRAINTS ';
  END LOOP;

END;
*/

CREATE TABLE Material (
    Name VARCHAR(255) PRIMARY KEY,
    Description VARCHAR (4000)
);

CREATE TABLE Consumable (
    Name VARCHAR (255) PRIMARY KEY,
    Recipe VARCHAR (4000)
    CHECK (
    Recipe = 'ATK' OR
    Recipe = 'Crit Rate' OR
    Recipe = 'DMG' OR
    Recipe = 'Recovery' OR
    Recipe = 'DEF'),
    FOREIGN KEY (Name) REFERENCES Material(Name) ON DELETE CASCADE
);

CREATE TABLE AscensionMaterial (
    Name VARCHAR(255) PRIMARY KEY,
    Tier INT NOT NULL CHECK (Tier < 6 AND Tier > 0),
    AscensionType VARCHAR(255)
    CHECK (
    AscensionType = 'Material' OR
    AscensionType = 'Weapon' OR
    AscensionType = 'Character'),
    FOREIGN KEY (Name) REFERENCES Material(Name) ON DELETE CASCADE
);

CREATE TABLE Region (
    Name VARCHAR(255) PRIMARY KEY,
    Lore VARCHAR (4000)
);

CREATE TABLE Subregion (
    RName VARCHAR(255),
    SubName VARCHAR(255),
    SubLore VARCHAR (4000),
    ZoneType VARCHAR(255),
    PRIMARY KEY (RName, SubName),
    FOREIGN KEY (RName) REFERENCES Region(Name) ON DELETE CASCADE
);

CREATE TABLE Weapon (
    Name VARCHAR(255) PRIMARY KEY,
    Tier VARCHAR(255) NOT NULL CHECK (Tier < 6 AND Tier > 0),
    Lore VARCHAR (4000),
    SetName VARCHAR(255),
    Dmg INT,
    Ability VARCHAR(255),
    WeaponType VARCHAR(255) NOT NULL
    CHECK (
    WeaponType = 'Bow' OR
    WeaponType = 'Sword' OR
    WeaponType = 'Claymore'OR
    WeaponType = 'Catalyst' OR
    WeaponType = 'Polearms'
    )
);

CREATE TABLE ArtifactSet (
    SetName VARCHAR(255) PRIMARY KEY,
    "4PieceBonus" VARCHAR (4000) UNIQUE NOT NULL,
    "2PieceBonus" VARCHAR (4000) UNIQUE NOT NULL,
    Tier INT CHECK (Tier < 6 AND Tier > 0)
);

CREATE TABLE Artifact (
    SetName VARCHAR(255),
    Name VARCHAR(255),
    ArtifactType VARCHAR(255)
	CHECK (
	ArtifactType = 'Flower of Life' OR
	ArtifactType = 'Plume of Death' OR
	ArtifactType = 'Sands of Eon' OR
	ArtifactType = 'Goblet of Eonothem' OR
	ArtifactType = 'Circlet of Logos'
	),
    Description VARCHAR (4000),
    PRIMARY KEY (SetName, Name),
    FOREIGN KEY (SetName) REFERENCES ArtifactSet(SetName) ON DELETE CASCADE
);

CREATE TABLE Stat (
    ID INT PRIMARY KEY,
    CritRate FLOAT,
    CritDMG FLOAT,
    Defense FLOAT,
    AttackDMG FLOAT,
    HP FLOAT
);

CREATE TABLE Enemies (
    Name VARCHAR(255) PRIMARY KEY,
    Description VARCHAR (4000),
    Lore VARCHAR (4000),
    StatID INT NOT NULL,
    FOREIGN KEY (StatID) REFERENCES Stat(ID) ON DELETE CASCADE
);

CREATE TABLE Boss (
    Name VARCHAR(255) PRIMARY KEY,
    RequiredResin INT,
    Phases INT,
    ElementalType VARCHAR(255)
    CHECK (
        ElementalType = 'Pyro' OR
        ElementalType = 'Hydro' OR
        ElementalType = 'Dendro' OR
        ElementalType = 'Electro' OR
        ElementalType = 'Cryo' OR
        ElementalType = 'Geo' OR
        ElementalType = 'Anemo'
    ),
    FOREIGN KEY (Name) REFERENCES Enemies(Name)
);

CREATE TABLE CHARACTER (
    Name VARCHAR(255) PRIMARY KEY,
    ElementalType VARCHAR(255) NOT NULL CHECK (
        ElementalType = 'Pyro' OR
        ElementalType = 'Hydro' OR
        ElementalType = 'Dendro' OR
        ElementalType = 'Electro' OR
        ElementalType = 'Cryo' OR
		ElementalType = 'Geo' OR
		ElementalType = 'Anemo'
    ),
    Gender VARCHAR(20)
    CHECK (
    Gender = 'Male' OR
    Gender = 'Female'),
    WeaponType VARCHAR(20) NOT NULL
    CHECK (
    WeaponType = 'Bow' OR
    WeaponType = 'Sword' OR
    WeaponType = 'Claymore'OR
    WeaponType = 'Catalyst' OR
    WeaponType = 'Polearms'
    ),
    Tier INT NOT NULL CHECK (Tier = 4 OR Tier = 5),
    Description VARCHAR (4000) UNIQUE,
    StatID INT NOT NULL,
    RegionName VARCHAR(255) NOT NULL,
    FOREIGN KEY (StatID) REFERENCES Stat(ID) ON DELETE CASCADE,
    FOREIGN KEY (RegionName) REFERENCES Region(Name) ON DELETE CASCADE
);

CREATE TABLE BossDrops (
    BossName VARCHAR(255),
    ArtifactSetName VARCHAR(255),
    PRIMARY KEY (BossName, ArtifactSetName ),
    FOREIGN KEY (BossName) REFERENCES Boss(Name),
    FOREIGN KEY (ArtifactSetName) REFERENCES ArtifactSet(SetName)
);

CREATE TABLE EnemyFoundAt (
    EnemyName VARCHAR(255),
    RegionName VARCHAR(255),
    PRIMARY KEY (EnemyName, RegionName),
    FOREIGN KEY (EnemyName) REFERENCES Enemies(Name),
    FOREIGN KEY (RegionName) REFERENCES Region(Name)
);

/*
CREATE ASSERTION TotalEnemyFoundAt
CHECK
	(NOT EXISTS ((SELECT Name FROM Enemies)
								EXCEPT
								(SELECT Name FROM Region)));
*/

CREATE TABLE EnemyDrops(
    EnemyName VARCHAR(255),
    MaterialName VARCHAR(255),
    PRIMARY KEY (EnemyName, MaterialName),
    FOREIGN KEY (EnemyName) REFERENCES Enemies(Name),
    FOREIGN KEY (MaterialName) REFERENCES Material(Name)
);

CREATE TABLE CharacterCanWield(
    CharacterName VARCHAR(255),
    WeaponName VARCHAR(255),
    Rank INT,
    PRIMARY KEY (CharacterName, WeaponName),
    FOREIGN KEY (CharacterName) REFERENCES CHARACTER(Name),
    FOREIGN KEY (WeaponName) REFERENCES Weapon(Name)
);

/*
CREATE ASSERTION TotalCharacterCanWield
CHECK
	(NOT EXISTS ((SELECT Name FROM `Character`)
								EXCEPT
								(SELECT Name FROM Weapon)));
*/

CREATE TABLE CharacterArtifacts(
    CharacterName VARCHAR(255),
    ArtifactSetName VARCHAR(255),
		Rank INT,
    PRIMARY KEY (CharacterName, ArtifactSetName),
    FOREIGN KEY (CharacterName) REFERENCES Character(Name),
    FOREIGN KEY (ArtifactSetName) REFERENCES ArtifactSet(SetName)
);

CREATE TABLE RequiredMaterialForWeapon(
    WeaponName VARCHAR(255),
    AscensionMaterial VARCHAR(255),
    Quantity INT,
    PRIMARY KEY (WeaponName, AscensionMaterial),
    FOREIGN KEY (WeaponName) REFERENCES Weapon(Name),
    FOREIGN KEY (AscensionMaterial) REFERENCES AscensionMaterial(Name)
);

CREATE TABLE MaterialsFoundAt(
    SubRegionName VARCHAR(255),
    RegionName VARCHAR(255),
    MaterialName VARCHAR(255),
    PRIMARY KEY(SubRegionName, MaterialName),
    FOREIGN KEY (RegionName, SubRegionName) REFERENCES Subregion(RName, SubName),
    FOREIGN KEY (MaterialName) REFERENCES Material(Name)
);

CREATE TABLE BattleInRegion (
    Battle VARCHAR(255) PRIMARY KEY,
    Region VARCHAR(255),
    FOREIGN KEY (Region) REFERENCES Region(Name)
);

CREATE TABLE CharacterInteractions (
    Char1 VARCHAR(255),
    Char2 VARCHAR(255),
    Battle VARCHAR(255),
    Contract VARCHAR(255),
    Friend VARCHAR(255),
    PRIMARY KEY (Char1, Char2),
    FOREIGN KEY (Char1) REFERENCES Character(Name),
    FOREIGN KEY (Char2) REFERENCES Character(Name),
		FOREIGN KEY (Battle) REFERENCES BattleInRegion(Battle)
);

CREATE TABLE ConsumableBoosts (
    ConsumableName VARCHAR(50),
    StatID INT NOT NULL,
		PRIMARY KEY (ConsumableName, StatID)
);

/*
CREATE ASSERTION TotalConsumableBoosts
CHECK
	(NOT EXISTS ((SELECT Name FROM Consumable)
								EXCEPT
								(SELECT ID FROM Stat)));
*/

CREATE TABLE RequiredMaterialForCharacter(
    CharacterName VARCHAR(255),
    AscensionMaterialName VARCHAR(255),
    Quantity INT,
    PRIMARY KEY (CharacterName, AscensionMaterialName),
    FOREIGN KEY (CharacterName) REFERENCES CHARACTER(Name),
    FOREIGN KEY (AscensionMaterialName) REFERENCES Material(Name)
);

/*
CREATE ASSERTION TotalRequiredMaterialForCharacter
CHECK
	(NOT EXISTS ((SELECT Name FROM 'CHARACTER')
								EXCEPT
								(SELECT Name FROM AscensionMaterial)));
*/

CREATE TABLE Enhances (
    StatID INT,
    ArtifactName VARCHAR(50),
    SetName VARCHAR(255),
    PRIMARY KEY (StatID, ArtifactName),
    FOREIGN KEY (StatID) REFERENCES Stat(ID),
    FOREIGN KEY (ArtifactName, SetName) REFERENCES Artifact(Name, SetName)
);

/*
CREATE ASSERTION TotalArtifactEnhances
CHECK
	(NOT EXISTS ((SELECT Name FROM Artifact)
								EXCEPT
								(SELECT ID FROM Stat)));
*/

INSERT ALL
INTO STAT VALUES (1, 0.15, null, null, null, null)
INTO Stat VALUES (2, null, 0.5, 169, 90, 2632)
INTO Stat VALUES (3, 0, null, 163.13, 88.04, 2731)
INTO Stat VALUES (4, null, null, null, 30, null)
INTO Stat VALUES (5, null, null, 505, 183, 1313)
INTO Stat VALUES (6, null, null, 505, 152, 1248)
INTO Stat VALUES (7, null, null, 505, 127, 510)
INTO Stat VALUES (8, null, null, 505, 152, 510)
INTO Stat VALUES (9, null, null, 505, 152, 437)
INTO Stat VALUES (10, null, null, 505, 183, 1313)
INTO Stat VALUES (11, null, null, 505, 34, 58)
INTO Stat VALUES (12, 0.05, 0.5, 44.27, 26.44, 807)
INTO Stat VALUES (13, 0.05, 0.5, 63, 23, 1039)
INTO Stat VALUES (14, 0.05, 0.5, 67, 16, 1012)
INTO Stat VALUES (15, 0.1, null, null, null, 320)
INTO Stat VALUES (16, null, null, null, null, 81)
INTO Stat VALUES (17, null, null, null, null, 0.28)
INTO Stat VALUES (18, null, null, null, null, 620)
INTO Stat VALUES (19, null, null, null, null, 645)
INTO Stat VALUES (20, null, null, 0.087, 0.07, 0.07)
INTO Stat VALUES (21, null, null, null, 47, null)
INTO Stat VALUES (22, 0.05, 0.5, 63, 23, 1020)
SELECT * FROM DUAL;

INSERT ALL
INTO Material VALUES ('Amakumo Fruit', 'The fruit of the Amakumo Grass, which grows on Seirai Island. You can hear it crackling with a tiny current if you hold it up to your ear.')
INTO Material VALUES ('Beryl Conch', 'A conch-like structure that gives off a faint glow. Despite the name, it is not a shell but something condensed from pure elemental energy.')
INTO Material VALUES ('Calla Lily', 'A flower that grows near water sources. When cooked, the petals have a chunky texture, yet are sweet and a little bitter.')
INTO Material VALUES ('Cor Lapis', 'A precious crystal of condensed pure Geo element that usually grows along with other minerals. It''s also commonly called ''Cor Petrae.''')
INTO Material VALUES ('Crystal Marrow', 'A crystal that contains a sliver of Tatarigami power. Adding this material during smelting will greatly increase the strength and toughness of metals.')
INTO Material VALUES ('Pile ''Em Up', 'A rich, meaty dish. Originally a Mondstadt dish made of steaks, potatoes, and cheese, it has since become synonymous with Ludi Harpastum.')
INTO Material VALUES ('Pure Water', 'It is said to be the legacy of a genius potioneer. It can draw out the purest strength from within one''s body')
INTO Material VALUES ('Almond Tofu', 'A dessert made out of almond. It has a silky-smooth texture with a long-lasting aroma of almond. It''s named tofu only because of its tofu-like shape.')
INTO Material VALUES ('Baklava', 'A traditional Sumeru dessert. This puff pastry contains chopped nuts and has been baked after having butter drizzled atop it — and not forgetting some syrup once it comes out of the oven. Some researchers will specially order this veritable ''fortress of sweetness'' to replenish their energies before an exam. This is also why this dish has great pride of place as the Akademiya''s favorite dessert.')
INTO Material VALUES ('Bamboo Shoot Soup', 'A soup dish that''s been stewed for a good long while. The meat and ham have been diced into small pieces, and the soup has been kept on low heat till it turned milky white. The process has brought out the distinct flavors of both kinds of meat, making for an especially tasty soup.')
INTO Material VALUES ('Sharp Arrowhead', 'A well-made arrowhead. Sharp enough to penetrate armor with the ease of a rock through the surface of water.')
INTO Material VALUES ('Weathered Arrowhead', 'An old arrowhead coated in blood. The arrowhead has long since lost its sharpness and thus its use as a weapon.
However it represents the pride of a hunter and acts as both an amulet and a medal.')
INTO Material VALUES ('Damaged Mask', 'A broken bone mask that once belonged to some hilichurl.Now more broken than complete, it can no longer perform its primary function.')
INTO Material VALUES ('Shivada Jade Gemstone', 'Character Ascension material.''sorry... to also have you shoulder the grievances of the world.''since you could endure my bitter cold, you must have the desire to burn? ''Then, burn away the old world for me.''')
INTO Material VALUES ('Shadow of the Warrior', 'A fragment of power that you obtained from defeating Childe, who had unleashed the might of his Delusion.
It is the product of a will to fight that has been honed over countless slaughters.If humans do indeed have destinies, then his must surely have been twisted by such deeds. Why else, then, would he always be at the heart of every conflict?')
INTO Material VALUES ('Echo of Scorching Might', 'A long time ago, the people of the Lord of Deserts carved these imperial insignias based on robust beetles to memorialize the bright sun that marked the shifting of day and night, as well as the infinity of such times.
Though the name of that Lord is no longer mentioned, these imperial insignias have been preserved through time, each bearing a faint ambiance of those past glories.
These insignias travel with the descendants of the Eremite Tribe, silently telling the tales of their then-Lord.')
INTO Material VALUES ('Mora', 'Common currency. The one language everybody speaks.')
INTO Material VALUES ('Brilliant Diamond Sliver', 'Character Ascension material.''Welcome to this world.''')
INTO Material VALUES ('Brilliant Diamond Fragment', 'Character Ascension material.''Welcome to this world.''')
INTO Material VALUES ('Brilliant Diamond Chunk', 'Character Ascension material.''Welcome to this world.''')
INTO Material VALUES ('Shackles of the Dandelion Gladiator', 'Vennessa was not truly bound by her chains. If she had so wished, no ordinary physical constraints forged in Mondstadt could have held her, for the land there yielded no ore of exceptional quality, nor did it possess the divine flame of her ancestral home. She was only bound by her responsibility to take care of her tribespeople.')
INTO Material VALUES ('Dream of the Dandelion Gladiator', 'Vennessa may have been freed from slavery, but she then found herself subject to many other, even greater forms of bondage. What began as a duty to her tribespeople extended to all of Mondstadt, then to the concept of freedom itself, and beyond a point even to the whole world. What she really hoped for, in truth, was that the world might grow stronger.')
INTO Material VALUES ('Echo of the Scorching Might', 'A long time ago, the people of the Lord of Deserts carved these imperial insignias based on robust beetles to memorialize the bright sun that marked the shifting of day and night, as well as the infinity of such times. Though the name of that Lord is no longer mentioned, these imperial insignias have been preserved through time, each bearing a faint ambiance of those past glories. These insignias travel with the descendants of the Eremite Tribe, silently telling the tales of their then-Lord.')
SELECT * FROM DUAL;

INSERT ALL
INTO Region VALUES ('Mondstadt', 'A city of freedom that lies in the northeast of Teyvat. From amongst mountains and wide-open plains, carefree breezes carry the scent of dandelions — a gift from the Anemo God, Barbatos — across Cider Lake to Mondstadt, which sits on an island in the middle of the lake.')
INTO Region VALUES ('Liyue', 'A bountiful harbor that lies in the east of Teyvat. Mountains stand tall and proud alongside the stone forest, that, together with the open plains and lively rivers, make up Liyue''s bountiful landscape, which shows its unique beauty through each of the four seasons. Just how many gifts from the Geo God lie in wait amongst the rocks of Liyue''s mountains?')
INTO Region VALUES ('Inazuma', 'An Isolated Archipelago Far East of Teyvat overcome endless thunderstorms and set foot on the islands of red maple and cherry blossoms. On winding shores and towering cliffs, and in forests and mountains full of secrets, witness the Eternity pursued by Her Excellency, the Almighty Narukami Ogosho.')
INTO Region VALUES ('Sumeru', 'The city of scholars located in the west-central part of Teyvat. A fantastical nation of both lush rainforest and barren desert, where countless fruits of wisdom grow and are buried. Whether Travelers travel from afar through the forest to reach the academy city or delve deep into the desert to discover the historical ruins of the red desert, a wealth of valuable knowledge awaits them here.')
INTO Region VALUES ('Fontaine', 'A terrestrial sea in the center of Teyvat. Following the direction of pure currents, crossing wilderness, the depths of the forests and vastness of the sea of sand, arriving at the origin of all the waters of the continent. At the top of the waterfall, in the depths of the capital atop the terrestrial sea... a story that has never been heard, a legend that has been forgotten, like a lost kingdom sunken beneath the waves, yearning for a bard to sing its drowned songs.')
SELECT * FROM DUAL;

INSERT ALL
INTO Consumable VALUES ('Pile ''Em Up', 'ATK')
INTO Consumable VALUES ('Pure Water', 'ATK')
INTO Consumable VALUES ('Almond Tofu', 'ATK')
INTO Consumable VALUES ('Baklava', 'ATK')
INTO Consumable VALUES ('Bamboo Shoot Soup', 'Recovery')
SELECT * FROM DUAL;

INSERT ALL
INTO Subregion VALUES ('Mondstadt', 'Wolvendom', 'A deep, dark woodland, where the silence sends chills down the spine. A wolf pack dwells in the shadows — one so dangerous that even the most fearless of hunters do not venture there lightly. The howling of the wolves at night can be heard from a long distance.', null)
INTO Subregion VALUES ('Liyue', 'Qiongji Estuary', 'Qiongji Estuary has 4 notable sub areas which are Guili Plains, Luhua Pool, Mingyun Village, Yaoguang Shoai', null)
INTO Subregion VALUES ('Inazuma', 'Narukami Island', 'A sight witnessed by all inbound travelers to Inazuma. The Grand Narukami Shrine and Sacred Sakura on the mountain, and the city of Inazuma on the coast.', null)
INTO Subregion VALUES ('Sumeru', 'Vanarana', 'No matter how dark the sky and land may get, as long as your spirit emits warmth like a golden sun, there will always be hope', null)
INTO Subregion VALUES ('Fontaine', 'Fontaine Research Institute of Kinetic Energy Engineering Region', 'It was largely destroyed in an explosion long ago. The explosion left behind structures such as Allogravity-Condensed Water Bodies floating in the air. Scientists such as Edwin Eastinghouse were researching the properties of Arkhium. The institute is also the site of the Experimental Field Generator boss.', null)
SELECT * FROM DUAL;

INSERT ALL
INTO AscensionMaterial VALUES ('Brilliant Diamond Sliver', 2, 'Character')
INTO AscensionMaterial VALUES ('Brilliant Diamond Fragment', 3, 'Character')
INTO AscensionMaterial VALUES ('Brilliant Diamond Chunk', 4, 'Character')
INTO AscensionMaterial VALUES ('Shackles of the Dandelion Gladiator', 4, 'Weapon')
INTO AscensionMaterial VALUES ('Dream of the Dandelion Gladiator', 5, 'Weapon')
INTO AscensionMaterial VALUES ('Echo of Scorching Might', 2, 'Weapon')
SELECT * FROM DUAL;


INSERT ALL
INTO Enemies VALUES ('Andrius', 'Andrius, also known as Lupus Boreas, was a powerful god in Mondstadt best known for his war against Decarabian, the God of Storms, during the Archon War. Towards the end of the era, Andrius chose to let his physical body die, leaving behind his spirit to watch over the land of Mondstadt.', 'Long in the past, Andrius'' powers were given to him by an ancient god. It is currently unknown who this god was, but this put Andrius on the level of a god himself. Sometime during the Archon War, hostility arose between Andrius and Decarabian. Andrius declared war on Decarabian while Decarabian enclosed his capital city — the area now known as Stormterror''s Lair — within a storm wall, thus starting the era known as the Age of Kings. They were collectively known as Kings of Ice and Frost and reached a stalemate: Andrius failed to land even a scratch on the Lord of the Tower, while Decarabian was either incapable of — or uninterested in — defeating Andrius. However, Decarabian''s people grew discontent with his rule. 2600 years ago, they rose up against him with the help of Barbatos, culminating in Decarabian''s death. At some point during the Archon War, Andrius decided that he was unworthy of becoming the Anemo Archon due to his perceived lack of love for humanity that an Archon ought to have. Realizing his blizzards could only take lives and not nourish them, he chose to pass on, letting his power flow into the land to nurture it and its people. It is unclear when exactly this occurred relative to Decarabian''s death, but it was likely around the same time or right beforehand. Upon the death of his physical body, part of Andrius'' spirit and power took on its current icy form. However, this form is incapable of harnessing his full abilities. Around 1000 years ago, after Vennessa''s rebellion against the Mondstadt Aristocracy, Andrius officially became one of the Four Winds of Mondstadt, while the Knight of Boreas — a position that currently holds an unknown relationship to him — joined the newly established Knights of Favonius. In the past, the Temple of the Wolf located near the Thousand Winds Temple was used to worship him, but it has long been abandoned, while Andrius'' spirit dwells on the other side of Mondstadt. Andrius would eventually become the leader of a group of wolves in Wolvendom, while continuing to accept abandoned infants into his pack. Sometime before the start of the game, he found and took in Razor. The Knights of Favonius'' current Grand Master, Varka, is also the current Knight of Boreas. Before he went on his expedition, he would occasionally visit Wolvendom; however, his relationship to Andrius is currently unclear.', 10)
INTO Enemies VALUES ('Tartaglia', 'Tartaglia uses the tall male model. He has short ginger hair and dull blue eyes. He wears a red Fatui mask pulled off to the left side of his head, and a beaded earring with a red crystal on his left ear. A few lighter blond streaks can be seen in his hair, with one prominent one on the left side of his bangs. Tartaglia wears his Blades of Glory outfit during the first phase of his Golden House battle. After the first phase, Tartaglia''s clothes turn from gray to black, he wears his mask on his face, and his Hydro Vision appears to be supplanted by his Electro Delusion. After the second phase, he undergoes a full outfit change and becomes larger, and his Vision reappears in the empty slot on his right chest while his Delusion moves to the center of his belt. Both his Vision and Delusion also obtain more prominent black and silver casings.', 'Tartaglia, also known by his codename "Childe," is a playable Hydro character in Genshin Impact. He is the Eleventh of the Eleven Fatui Harbingers. Wherever he goes, danger follows, and Childe is always eager for a challenge, making him extremely dangerous despite being the youngest member.', 9)
INTO Enemies VALUES ('Electro Hypostasis', 'The Electro Hypostasis is a Normal Boss challenge found in Cape Oath, Galesong Hill, Mondstadt.', 'Elemental hypostases are the highest forms of elemental structures, usually formed either at a location bursting with elemental energy or at a clogged ley line. Elemental hypostases have developed defensive mechanisms based on their elemental attributes', 8)
INTO Enemies VALUES ('Cryo Regisvine', 'Elemental hypostases are the highest forms of elemental structures, usually formed either at a location bursting with elemental energy or at a clogged ley line. Elemental hypostases have developed defensive mechanisms based on their elemental attributes', 'A monster formed from a vine that was imbued with the essence of biting frost within the ley lines.
Some studies suggest that plants are like the organs of the world, harmonizing the turbulent elemental energies of the ley lines. Concrete examples of this phenomena are Mist Flowers, Whopperflowers, and the like, which brim over with elemental energy. In certain circumstances, certain plants will turn into creatures of monstrous size and intent — such as the Cryo Regisvines — in the course of many years.', 7)
INTO Enemies VALUES ('Aeonblight Drake', 'The Aeonblight Drake is a Normal Boss challenge found in Devantaka Mountain, Ardravi Valley, Sumeru.', 'This dragon-shaped combat machine is a being that inspires fear, much like the lord who once ruled these vast lands. This mysterious, tireless mechanical monstrosity seems to be proof that the now-destroyed realm had reached heights that mortals should never have.', 5)
INTO Enemies VALUES ('Pyro Hilichurl Shooter', 'The primitive wandering inhabitants of Teyvat''s wildernesses.
These archers wield simple crossbows, and their arrowheads are coated with a thin layer of flammable material that can set their targets on fire. El Musk of the previous generation believed that this is an innovation by brighter hilichurls who wished to cook their game after hunting it.', null, 11)
SELECT * FROM DUAL;

INSERT ALL
INTO Boss VALUES ('Andrius', 0 ,2, 'Cryo')
INTO Boss VALUES ('Tartaglia', 40 ,3, 'Hydro')
INTO Boss VALUES ('Electro Hypostasis', 40 , 1, 'Electro')
INTO Boss VALUES ('Cryo Regisvine', 40, 1, 'Cryo')
INTO Boss VALUES ('Aeonblight Drake', 40 ,1, 'Cryo')
SELECT * FROM DUAL;

INSERT ALL
INTO Weapon VALUES ('Amos'' Bows', 5, 'An extremely ancient bow that has retained its power despite its original master being long gone. It draws power from everyone and everything in the world, and the further away you are from that which your heart desires, the more powerful it is.', NULL, 46, 'Increases Normal Attack and Aimed Shot DMG by 18%. Increases DMG by 12% for every 0.1s that an arrow is in flight. Stacks up to 5 times on each arrow.',  'Bow')
INTO Weapon VALUES ('Tulaytullah''s Remembrance', 5, 'Normal Attack SPD is increased by 10%. After the wielder unleashes an Elemental Skill, Normal Attack DMG will increase by 4.8% every second for 14s. After hitting an opponent with a Normal Attack during this duration, Normal Attack DMG will be increased by 9.6%. This increase can be triggered once every 0.3s. The maximum Normal Attack DMG increase per single duration of the overall effect is 48%.
', NULL, 48, 'A bell crafted of deep sapphire and sterling silver. Its echoes are crisp as they are distant.', 'Catalyst')
INTO Weapon VALUES ('Wolf''s Gravestone', 5, 'Increases ATK by 20%. On hit, attacks against enemies with less than 30% HP increases all party members'' ATK by 40% for 12s. Can only occur once every 30s.', NULL, 46, 'A longsword used by the Wolf Knight. Originally just a heavy sheet of Iron given to the knight by a blacksmith from the city, it became endowed with legendary power owing to his friendship with the wolves.', 'Claymore')
INTO Weapon VALUES ('Prototype Crescent', 4, 'Aimed Shot hits on weak points increase Movement SPD by 10% and ATK by 36% for 10s.', NULL, 41, 'A prototype longbow discovered in the Blackcliff Forge. The arrow fired form this bow glimmers like a ray of moonlight.', 'Bow')
INTO Weapon VALUES ('Freedom-Sworn', 5, 'Increases DMG by 10%. When characters with Freedom-Sworn trigger Elemental Reactions, they gain a Sigil of Rebellion (once every 0.5s). This triggers even if they aren''t on the field. When you reach 2 Sigils, they will be consumed which grants nearby party members 20% ATK and 16% Normal, Charged, and Plunging Attack DMG for 12s. Once triggered, you gain no Sigils for 20s. The same buffs from the Millennial Movement series does not stack.', NULL, 46, 'A straight sword, azure as antideluvian song, and as keen as the oaths of freedom taken in the Land of Wind.', 'Sword')
SELECT * FROM DUAL;

INSERT ALL
INTO ArtifactSet VALUES ('Blizzard Strayer', 'When a character attacks an enemy affected by Cryo, their CRIT Rate is increased by 20%. If the enemy is Frozen, CRIT Rate is increased by an additional 20%.', 'Cryo DMG Bonus +15%', 5)
INTO ArtifactSet VALUES ('Desert Pavillion Chronicle', 'When Charged Attacks hit opponents, the equipping character''s Normal Attack speed will increase by 10% while Normal, Charged, and Plunging Attack DMG will increase by 40% for 15s', 'Anemo DMG Bonus +15%', 5)
INTO ArtifactSet VALUES ('Gladiator''s Finale', 'If the wielder of this artifact set uses a Sword, Claymore or Polearm, increases their Normal Attack DMG by 35%.', 'ATK +18%.', 5)
INTO ArtifactSet VALUES ('Wanderer''s Troupe', 'Increases Charged Attack DMG by 35% if the character uses a Catalyst or a Bow.', 'Increases Elemental Mastery by 80.', 5)
INTO ArtifactSet VALUES ('Berserker', 'When HP is below 70%, CRIT Rate increases by an additional 24%.', 'CRIT Rate +12%', 5)
SELECT * FROM DUAL;

INSERT ALL
INTO Artifact VALUES ('Wanderer''s Troupe', 'Troupe''s Dawnlight', 'Flower of Life', 'A small flower-shaped insignia. There seems to be music coming from it. Among the members of the troupe was a charismatic swordswoman. Beautiful as the light on water, elegant as the chirping lark.
Her every slash hummed a tune of the wind. The end of each song and dance was like the sun after the rain. Dust has since settled, and both the stage and life itself feel emptier for the absence of her music. Both her music and her sword were as lethal as they were beautiful.
This was in keeping with the troupe''s performances, which were intended for two very different audiences. For the audience of foes, the music drifted far beyond the stage.')
INTO Artifact VALUES ('Wanderer''s Troupe', 'Concert''s Final Hour', 'Sands of Eon', 'The troupe''s hourglass, which is also a harp. The tune becomes deeper with the passage of time. When the performance was coming to an end, the troupe would play this harp. As time passed, the tune of the harp became deeper.
The sound of the harp dying down marked the end of the show. Everything has an end, and the troupe was no exception. One by one, each member of the troupe met their fate and their instruments were buried deep in the dust. As the harp fell silent, the final sound heard from the musical troupe was the faint trickle of sand as it slid down the hourglass one final time.')
INTO Artifact VALUES ('Blizzard Strayer', 'Icebreaker''s Resolve', 'Plume of Death', 'A feather of a bird of prey that did not originally belong in the winter chill. It is cold to the touch. And when you do touch it, it is as if you can hear cries in the snowstorm, unbowed but bereft of hope. With no nest or shelter, the bird faced the storms proudly. This feather has been assailed with coalescing ice and snow, leaving it coated in pearls of frost. It once came from a falcon in flight and was plucked violently from that bird by the frigid winds. It drifted a while in the air before the weight of the frost dragged it back down to the ground. "I believe that the lively birds will follow you, and return to the gardens of the summer palace, now green once more." "Those who have been chased away by the chilling tide, and those children who have lost their homes, they will return with you to the home of their dreams." The hero, bearing such great burdens on his back, halted in the whipping snow, struggling to discern the color of that feather. Sealed in ice, that feather''s color faded every step of the way — just like the hero''s task.')
INTO Artifact VALUES ('Berserker', 'Berserker''s Battle Mask', 'Circlet of Logos', 'Bathed in the flames that devoured his homeland, the berserker''s face was no longer recognizable. The iron mask became joined with his flesh, permanently branding his face with a heartless countenance. Then, in a fierce battle, his horrifying mask was split into two by his opponent. The cracks of the mask tore apart the flesh that had long stuck to it. But neither pain nor blood could stop the berserker''s unfaltering footsteps. The berserker kept roaring until the fresh blood covered the black clots hardened on his face. ')
INTO Artifact VALUES ('Gladiator''s Finale', 'Gladiator''s Intoxication', 'Goblet of Eonothem', 'A lavish goblet made of gold. It was a gift from the champion gladiator''s master. From this goblet the champion gladiator drank, be of fine wine or the blood of the defeated. Another victory for the gladiator. Covered in wounds, he dedicated the victory to his master. More intoxicating and pain numbing than the fine wine was the victory, honor, and applause from the crowd. The master allowed him to join the feast and presented him with this goblet. It was an exquisite goblet, made specially for him. A symbol of how much his master cared. But the golden chains of vanity had bound the gladiator and the poisonous snake of desire had strangled him. By pausing to wait for the wine of victory, the gladiator missed his chance to seize a fleeting chance at freedom.')
SELECT * FROM DUAL;

INSERT ALL
INTO Character VALUES ('Yae Miko', 'Electro', 'Female', 'Catalyst', 5, 'Lady Guuji of the Grand Narukami Shrine. Also serves as the editor-in-chief of Yae Publishing House. Unimaginable intelligence and cunning are hidden under her beautiful appearance.', 12, 'Inazuma')
INTO Character VALUES ('Kazuha', 'Anemo', 'Male', 'Sword', 5, 'A wandering samurai from Inazuma who is currently with Liyue''s Crux Fleet. A gentle and carefree soul whose heart hides a great many burdens from the past.', 13, 'Inazuma')
INTO Character VALUES ('Ganyu', 'Cryo', 'Female', 'Bow', 5, 'The secretary at Yuehai Pavilion. The blood of the qilin, an illuminated beast, flows within her veins.', 2, 'Liyue')
INTO Character VALUES ('Wanderer', 'Anemo', 'Male', 'Catalyst', 5, 'A wayfaring figure whose identity is a mystery. He dresses like a mountain ascetic, but he certainly does not act the part.', 3, 'Sumeru')
INTO Character VALUES ('Noelle', 'Geo', 'Female', 'Claymore', 4, 'A maid who faithfully serves the Knights of Favonius. She dreams of joining their ranks someday.', 14, 'Mondstadt')
INTO Character VALUES ('Childe', 'Hydro' , 'Male', 'Sword', 5, 'Childe Tartaglia, Eleventh of the Fatui Harbingers. He draws power from the ominous Delusion he possesses and fights using martial arts that he learned in the land of darkness. He is a pure warrior with an insatiable lust for battle.', 22, 'Liyue')
SELECT * FROM DUAL;

INSERT ALL
INTO BossDrops VALUES ('Andrius', 'Gladiator''s Finale')
INTO BossDrops VALUES ('Andrius', 'Wanderer''s Troupe')
INTO BossDrops VALUES ('Tartaglia', 'Gladiator''s Finale')
INTO BossDrops VALUES ('Electro Hypostasis', 'Gladiator''s Finale')
INTO BossDrops VALUES ('Cryo Regisvine', 'Wanderer''s Troupe')
SELECT * FROM DUAL;

INSERT ALL
INTO EnemyFoundAt VALUES ('Andrius', 'Mondstadt')
INTO EnemyFoundAt VALUES ('Cryo Regisvine', 'Mondstadt')
INTO EnemyFoundAt VALUES ('Aeonblight Drake', 'Sumeru')
INTO EnemyFoundAt VALUES ('Electro Hypostasis', 'Mondstadt')
INTO EnemyFoundAt VALUES ('Tartaglia', 'Liyue')
INTO EnemyFoundAt VALUES ('Pyro Hilichurl Shooter', 'Mondstadt')
INTO EnemyFoundAt VALUES ('Pyro Hilichurl Shooter', 'Liyue')
INTO EnemyFoundAt VALUES ('Pyro Hilichurl Shooter', 'Sumeru')
SELECT * FROM DUAL;

INSERT ALL
INTO EnemyDrops VALUES ('Pyro Hilichurl Shooter', 'Sharp Arrowhead')
INTO EnemyDrops VALUES ('Pyro Hilichurl Shooter', 'Weathered Arrowhead')
INTO EnemyDrops VALUES ('Pyro Hilichurl Shooter', 'Damaged Mask')
INTO EnemyDrops VALUES ('Tartaglia', 'Shadow of the Warrior')
INTO EnemyDrops VALUES ('Aeonblight Drake', 'Shivada Jade Gemstone')
SELECT * FROM DUAL;

INSERT ALL
INTO CharacterCanWield VALUES ('Wanderer', 'Tulaytullah''s Remembrance', 1)
INTO CharacterCanWield VALUES ('Ganyu', 'Amos'' Bows', 1)
INTO CharacterCanWield VALUES ('Ganyu', 'Prototype Crescent', 2)
INTO CharacterCanWield VALUES ('Noelle', 'Wolf''s Gravestone', 2)
INTO CharacterCanWield VALUES ('Childe', 'Prototype Crescent', 2)
INTO CharacterCanWield VALUES ('Kazuha', 'Freedom-Sworn', 1)
INTO CharacterCanWield VALUES ('Yae Miko', 'Tulaytullah''s Remembrance', 1)
SELECT * FROM DUAL;

INSERT ALL
INTO CharacterArtifacts VALUES ('Ganyu', 'Wanderer''s Troupe', 1)
INTO CharacterArtifacts VALUES ('Ganyu', 'Blizzard Strayer', 2)
INTO CharacterArtifacts VALUES ('Wanderer', 'Desert Pavillion Chronicle', 1)
INTO CharacterArtifacts VALUES ('Wanderer', 'Gladiator''s Finale', 2)
INTO CharacterArtifacts VALUES ('Kazuha', 'Desert Pavillion Chronicle', 1)
SELECT * FROM DUAL;

INSERT ALL
INTO RequiredMaterialForWeapon VALUES ('Tulaytullah''s Remembrance', 'Echo of Scorching Might', 5)
INTO RequiredMaterialForWeapon VALUES ('Amos'' Bows', 'Shackles of the Dandelion Gladiator', 14)
INTO RequiredMaterialForWeapon VALUES ('Amos'' Bows', 'Dream of the Dandelion Gladiator', 6)
INTO RequiredMaterialForWeapon VALUES ('Freedom-Sworn', 'Shackles of the Dandelion Gladiator', 14)
INTO RequiredMaterialForWeapon VALUES ('Freedom-Sworn', 'Dream of the Dandelion Gladiator', 6)
SELECT * FROM DUAL;

INSERT ALL
INTO MaterialsFoundAt VALUES ('Qiongji Estuary', 'Liyue', 'Cor Lapis')
INTO MaterialsFoundAt VALUES ('Narukami Island', 'Inazuma', 'Amakumo Fruit')
INTO MaterialsFoundAt VALUES ('Wolvendom', 'Mondstadt', 'Calla Lily')
INTO MaterialsFoundAt VALUES ('Fontaine Research Institute of Kinetic Energy Engineering Region', 'Fontaine', 'Beryl Conch')
INTO MaterialsFoundAt VALUES ('Narukami Island', 'Inazuma', 'Crystal Marrow')
SELECT * FROM DUAL;

INSERT ALL
INTO BattleInRegion VALUES ('Enkanomiya clash with the Dragonheir of the Depths', 'Inazuma')
INTO BattleInRegion VALUES ('Fall of Decarabian''s Mondstadt', 'Mondstadt')
INTO BattleInRegion VALUES ('Guili Assembly', 'Liyue')
INTO BattleInRegion VALUES ('Dendro Archon Conflict', 'Sumeru')
INTO BattleInRegion VALUES ('Vision Hunt Decree', 'Inazuma')
INTO BattleInRegion VALUES ('Liyue Archon War', 'Liyue')
SELECT * FROM DUAL;

INSERT ALL
INTO CharacterInteractions VALUES ('Childe', 'Noelle', null, 'Owe Money', 'TRUE')
INTO CharacterInteractions VALUES ('Yae Miko', 'Kazuha', 'Vision Hunt Decree', null, 'FALSE')
INTO CharacterInteractions VALUES ('Ganyu', 'Childe', 'Liyue Archon War', null, 'FALSE')
INTO CharacterInteractions VALUES ('Wanderer', 'Childe', null, null, 'FALSE')
INTO CharacterInteractions VALUES ('Wanderer', 'Yae Miko', 'Dendro Archon Conflict', null, 'TRUE')
SELECT * FROM DUAL;

INSERT ALL
INTO ConsumableBoosts VALUES ('Pile ‘Em Up', 1)
INTO ConsumableBoosts VALUES ('Pure Water', 15)
INTO ConsumableBoosts VALUES ('Almond Tofu', 16)
INTO ConsumableBoosts VALUES ('Baklava', 1)
INTO ConsumableBoosts VALUES ('Bamboo Shoot Soup', 17)
INTO ConsumableBoosts VALUES ('Bamboo Shoot Soup', 18)
SELECT * FROM DUAL;

INSERT ALL
INTO RequiredMaterialForCharacter VALUES ('Ganyu', 'Echo of Scorching Might', 6)
INTO RequiredMaterialForCharacter VALUES ('Ganyu', 'Dream of the Dandelion Gladiator', 7)
INTO RequiredMaterialForCharacter VALUES ('Kazuha', 'Brilliant Diamond Chunk', 3)
INTO RequiredMaterialForCharacter VALUES ('Wanderer', 'Echo of Scorching Might', 5)
INTO RequiredMaterialForCharacter VALUES ('Noelle', 'Brilliant Diamond Fragment', 6)
INTO RequiredMaterialForCharacter VALUES ('Yae Miko', 'Brilliant Diamond Chunk', 4)
INTO RequiredMaterialForCharacter VALUES ('Childe', 'Brilliant Diamond Fragment', 4)
SELECT * FROM DUAL;

INSERT ALL
INTO Enhances VALUES (19, 'Troupe''s Dawnlight', 'Wanderer''s Troupe')
INTO Enhances VALUES (20, 'Concert''s Final Hour', 'Wanderer''s Troupe')
INTO Enhances VALUES (1, 'Troupe''s Dawnlight', 'Wanderer''s Troupe')
INTO Enhances VALUES (2, 'Icebreaker''s Resolve', 'Blizzard Strayer')
INTO Enhances VALUES (2, 'Gladiator''s Intoxication', 'Gladiator''s Finale')
INTO Enhances VALUES (2, 'Berserker''s Battle Mask', 'Berserker')
SELECT * FROM DUAL;