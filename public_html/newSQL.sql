
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

CREATE TABLE RequiredMaterialForCharacter(
    CharacterName VARCHAR(255),
    AscensionMaterialName VARCHAR(255),
    Quantity INT,
    PRIMARY KEY (CharacterName, AscensionMaterialName),
    FOREIGN KEY (CharacterName) REFERENCES CHARACTER(Name),
    FOREIGN KEY (AscensionMaterialName) REFERENCES Material(Name)
);


CREATE TABLE Enhances (
    StatID INT,
    ArtifactName VARCHAR(50),
    SetName VARCHAR(255),
    PRIMARY KEY (StatID, ArtifactName),
    FOREIGN KEY (StatID) REFERENCES Stat(ID),
    FOREIGN KEY (ArtifactName, SetName) REFERENCES Artifact(Name, SetName)
);
