--
-- Contenu de la table `FraisForfait`
--
INSERT INTO `FraisForfait` (`id`, `libelle`, `montant`)
VALUES ('ETP', 'Forfait Etape', 110.00),
    ('KM', 'Frais Kilométrique', 0.62),
    ('NUI', 'Nuitée Hôtel', 80.00),
    ('REP', 'Repas Restaurant', 25.00);
--
-- Contenu de la table `LigneFraisForfait`
--
--
-- Contenu de la table `Etat`
--
INSERT INTO `Etat` (`id`, `libelle`)
VALUES ('RB', 'Remboursée'),
    ('CL', 'Saisie clôturée'),
    ('CR', 'Fiche créée, saisie en cours'),
    ('VA', 'Validée et mise en paiement');
-- --------------------------------------------------------
--
-- Contenu de la table `Visiteur`
--
INSERT INTO `Visiteur` (
        `id`,
        `nom`,
        `prenom`,
        `login`,
        `mdp`,
        `adresse`,
        `cp`,
        `ville`,
        `dateEmbauche`
    )
VALUES (
        'a131',
        'Villechalane',
        'Louis',
        'lvillachane',
        'jux7g',
        '8 rue des Charmes',
        '46000',
        'Cahors',
        '2005-12-21'
    ),
    (
        'a17',
        'Andre',
        'David',
        'dandre',
        'oppg5',
        '1 rue Petit',
        '46200',
        'Lalbenque',
        '1998-11-23'
    ),
    (
        'a55',
        'Bedos',
        'Christian',
        'cbedos',
        'gmhxd',
        '1 rue Peranud',
        '46250',
        'Montcuq',
        '1995-01-12'
    ),
    (
        'a93',
        'Tusseau',
        'Louis',
        'ltusseau',
        'ktp3s',
        '22 rue des Ternes',
        '46123',
        'Gramat',
        '2000-05-01'
    ),
    (
        'b13',
        'Bentot',
        'Pascal',
        'pbentot',
        'doyw1',
        '11 allée des Cerises',
        '46512',
        'Bessines',
        '1992-07-09'
    ),
    (
        'b16',
        'Bioret',
        'Luc',
        'lbioret',
        'hrjfs',
        '1 Avenue gambetta',
        '46000',
        'Cahors',
        '1998-05-11'
    ),
    (
        'b19',
        'Bunisset',
        'Francis',
        'fbunisset',
        '4vbnd',
        '10 rue des Perles',
        '93100',
        'Montreuil',
        '1987-10-21'
    ),
    (
        'b25',
        'Bunisset',
        'Denise',
        'dbunisset',
        's1y1r',
        '23 rue Manin',
        '75019',
        'paris',
        '2010-12-05'
    ),
    (
        'b28',
        'Cacheux',
        'Bernard',
        'bcacheux',
        'uf7r3',
        '114 rue Blanche',
        '75017',
        'Paris',
        '2009-11-12'
    ),
    (
        'b34',
        'Cadic',
        'Eric',
        'ecadic',
        '6u8dc',
        '123 avenue de la République',
        '75011',
        'Paris',
        '2008-09-23'
    ),
    (
        'b4',
        'Charoze',
        'Catherine',
        'ccharoze',
        'u817o',
        '100 rue Petit',
        '75019',
        'Paris',
        '2005-11-12'
    ),
    (
        'b50',
        'Clepkens',
        'Christophe',
        'cclepkens',
        'bw1us',
        '12 allée des Anges',
        '93230',
        'Romainville',
        '2003-08-11'
    ),
    (
        'b59',
        'Cottin',
        'Vincenne',
        'vcottin',
        '2hoh9',
        '36 rue Des Roches',
        '93100',
        'Monteuil',
        '2001-11-18'
    ),
    (
        'd13',
        'Debelle',
        'Jeanne',
        'jdebelle',
        'nvwqq',
        '134 allée des Joncs',
        '44000',
        'Nantes',
        '2000-05-11'
    ),
    (
        'd51',
        'Debroise',
        'Michel',
        'mdebroise',
        'sghkb',
        '2 Bld Jourdain',
        '44000',
        'Nantes',
        '2001-04-17'
    ),
    (
        'e22',
        'Desmarquest',
        'Nathalie',
        'ndesmarquest',
        'f1fob',
        '14 Place d Arc',
        '45000',
        'Orléans',
        '2005-11-12'
    ),
    (
        'e24',
        'Desnost',
        'Pierre',
        'pdesnost',
        '4k2o5',
        '16 avenue des Cèdres',
        '23200',
        'Guéret',
        '2001-02-05'
    ),
    (
        'e39',
        'Dudouit',
        'Frédéric',
        'fdudouit',
        '44im8',
        '18 rue de l église',
        '23120',
        'GrandBourg',
        '2000-08-01'
    ),
    (
        'e49',
        'Duncombe',
        'Claude',
        'cduncombe',
        'qf77j',
        '19 rue de la tour',
        '23100',
        'La souteraine',
        '1987-10-10'
    ),
    (
        'e5',
        'Enault-Pascreau',
        'Céline',
        'cenault',
        'y2qdu',
        '25 place de la gare',
        '23200',
        'Gueret',
        '1995-09-01'
    ),
    (
        'e52',
        'Eynde',
        'Valérie',
        'veynde',
        'i7sn3',
        '3 Grand Place',
        '13015',
        'Marseille',
        '1999-11-01'
    ),
    (
        'f21',
        'Finck',
        'Jacques',
        'jfinck',
        'mpb3t',
        '10 avenue du Prado',
        '13002',
        'Marseille',
        '2001-11-10'
    ),
    (
        'f39',
        'Frémont',
        'Fernande',
        'ffremont',
        'xs5tq',
        '4 route de la mer',
        '13012',
        'Allauh',
        '1998-10-01'
    ),
    (
        'f4',
        'Gest',
        'Alain',
        'agest',
        'dywvt',
        '30 avenue de la mer',
        '13025',
        'Berre',
        '1985-11-01'
    );
-- --------------------------------------------------------
--
-- Contenu de la table `Comptable`
--
INSERT INTO `Comptable` (`id`, `nom`, `prenom`, `login`, `mdp`)
VALUES (
        'g110',
        'Blanc',
        'Skyler',
        'sblanc',
        'hsbrg'
    ),
    (
        'g77',
        'Zucker',
        'Marcus',
        'mzucker',
        'fb2os'
    );
-- --------------------------------------------------------