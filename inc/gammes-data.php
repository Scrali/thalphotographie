<?php
/**
 * Contenu des cinq gammes : textes, repères et formules.
 * Source unique utilisée par les pages de gamme et par la liste des
 * prestations sur l'accueil. Les tarifs doivent rester alignés avec
 * thal-studio/includes/pricing.php.
 */

function thal_gammes(): array
{
    return [
        'identite' => [
            'nav'        => 'Identité',
            'kicker'     => 'Photos d’identité',
            'title'      => 'Photo d’identité à domicile',
            'metaTitle'  => 'Photo d’identité à domicile',
            'metaDesc'   => 'Photo d’identité aux normes fedpol, prise et imprimée chez vous. Passeport, carte d’identité, visa et permis de séjour.',
            'lead'       => 'Format et fond conformes aux exigences fedpol pour passeport, carte d’identité, visa et permis de séjour. Je me déplace à votre domicile avec le matériel de prise de vue et d’impression : photo prise et imprimée sur place, contrôle qualité inclus, zéro mauvaise surprise au guichet.',
            'from'       => 'dès 45 CHF',
            'summary'    => '4 photos imprimées sur place, zone 15 km incluse',
            'carousel'   => ['key' => 'identite', 'category' => 'Identité', 'label' => 'Photos d’identité'],
            'facts'      => [
                ['Lieu', 'À votre domicile'],
                ['Zone incluse', '15 km depuis Sainte-Croix'],
                ['Sur place', 'Prise de vue et impression'],
                ['Conformité', 'fedpol, passeport, visa, permis'],
            ],
            'pricesIntro' => 'Le tarif est dégressif : chaque personne photographiée pendant le même passage bénéficie du tarif réduit.',
            'cta'        => 'Prendre rendez-vous',
            'cards'      => [
                [
                    'h' => '1 personne', 'dur' => '4 photos, zone 15 km incluse',
                    'amount' => '45', 'unit' => 'CHF',
                    'li' => ['Prise de vue conforme fedpol', 'Impression sur place, format officiel', 'Contrôle qualité inclus'],
                    'prefill' => 'Bonjour, je souhaite prendre rendez-vous pour une photo d’identité à domicile.',
                ],
                [
                    'h' => '2<sup>e</sup> personne', 'dur' => 'Même passage',
                    'amount' => '20', 'unit' => 'CHF',
                    'li' => ['4 photos imprimées', 'Aucun déplacement supplémentaire'],
                    'prefill' => 'Bonjour, photo d’identité à domicile pour 2 personnes (45 CHF + 20 CHF).',
                ],
                [
                    'h' => '3<sup>e</sup> personne et plus', 'dur' => 'Même passage',
                    'amount' => '10', 'unit' => 'CHF par personne',
                    'li' => ['4 photos imprimées', 'Idéal pour les familles'],
                    'prefill' => 'Bonjour, photo d’identité à domicile pour plusieurs personnes (tarif dégressif).',
                ],
            ],
            'note'       => 'Zone incluse : 15 km depuis Sainte-Croix. Au-delà : 0.75 CHF/km. <a href="https://www.google.com/maps/dir/?api=1&amp;origin=Sainte-Croix,+VD,+Suisse" target="_blank" rel="noopener">Vérifiez votre distance sur Google Maps</a>, ou indiquez simplement votre localité dans le message de contact : je confirme le tarif exact avant de valider le rendez-vous.',
            'disclaimer' => 'Service à domicile, zone 15 km incluse (depuis Sainte-Croix). Au-delà : 0.75 CHF/km. Devis personnalisé gratuit sous 48h. TVA non applicable.',
        ],

        'portraits' => [
            'nav'        => 'Portraits',
            'kicker'     => 'Portraits',
            'title'      => 'Des portraits qui vous ressemblent.',
            'metaTitle'  => 'Portraits',
            'metaDesc'   => 'Séances portrait individuel, duo, grossesse ou famille, chez vous ou sur le lieu de votre choix. Retouche et livraison privée sécurisée incluses.',
            'lead'       => 'Trois formules pensées pour un portrait individuel, un moment à deux ou une séance en famille, avec un résultat livré prêt à partager. La séance a lieu chez vous ou sur le lieu de votre choix : je me déplace avec mon matériel.',
            'from'       => 'dès 195 CHF',
            'summary'    => 'Individuel, duo, grossesse ou famille',
            'carousel'   => ['key' => 'portraits', 'category' => 'Portraits', 'label' => 'Portraits'],
            'facts'      => [
                ['Lieu', 'Chez vous ou lieu de votre choix'],
                ['Durée', '45 min à 1 h 30'],
                ['Photos retouchées', '10 à 20'],
                ['Livraison', 'SwissTransfer sécurisé'],
            ],
            'pricesIntro' => 'Trois formules selon le nombre de personnes et le temps passé ensemble.',
            'cta'        => 'Demander un devis',
            'cards'      => [
                [
                    'h' => 'Individuel', 'dur' => 'Séance de 45 min',
                    'amount' => '195', 'unit' => 'CHF dès',
                    'li' => ['10 photos retouchées', 'Sélection accompagnée', 'Livraison privée sécurisée'],
                    'prefill' => 'Formule Individuel (45 min, 10 photos), dès 195 CHF.',
                ],
                [
                    'h' => 'Duo / Grossesse', 'dur' => 'Séance de 1 h',
                    'amount' => '245', 'unit' => 'CHF dès',
                    'li' => ['15 photos retouchées', 'Sélection accompagnée', 'Livraison privée sécurisée'],
                    'prefill' => 'Formule Duo / Grossesse (1 h, 15 photos), dès 245 CHF.',
                ],
                [
                    'h' => 'Famille', 'dur' => 'Séance de 1 h 30',
                    'amount' => '325', 'unit' => 'CHF dès',
                    'li' => ['20 photos retouchées', 'Sélection accompagnée', 'Livraison privée sécurisée'],
                    'prefill' => 'Formule Famille (1 h 30, 20 photos), dès 325 CHF.',
                ],
            ],
            'highlight'  => [
                'h' => 'Grand groupe',
                'p' => 'Pour une école, un groupe d’amis ou une famille élargie, un devis personnalisé permet d’organiser la séance selon vos contraintes d’horaire et de lieu.',
                'b' => 'Demander un devis groupe',
                'prefill' => 'Demande de devis groupe (école, amis, famille élargie).',
            ],
            'note'       => 'Chaque séance comprend la sélection, la retouche professionnelle et la livraison privée sécurisée via SwissTransfer.',
            'disclaimer' => 'Tarifs de base, hors déplacement au-delà de 20 km. Devis personnalisé gratuit sous 48h. TVA non applicable.',
        ],

        'animaux' => [
            'nav'        => 'Animaux de compagnie',
            'kicker'     => 'Animaux de compagnie',
            'title'      => 'Des photos qui capturent leur personnalité.',
            'metaTitle'  => 'Photos d’animaux de compagnie',
            'metaDesc'   => 'Séance photo pour votre chien, chat ou autre compagnon, en intérieur ou en extérieur, chez vous ou sur le lieu de votre choix.',
            'lead'       => 'Chien, chat ou autre compagnon : une séance en intérieur ou en extérieur, chez vous ou sur le lieu de votre choix, pensée pour mettre en valeur son caractère.',
            'from'       => 'dès 165 CHF',
            'summary'    => 'Intérieur ou extérieur, chien, chat et compagnie',
            'carousel'   => ['key' => 'animaux', 'category' => 'Animaux', 'label' => 'Animaux de compagnie'],
            'facts'      => [
                ['Lieu', 'Chez vous, ou en extérieur'],
                ['Durée', '30 min à 1 h'],
                ['Photos retouchées', '8 à 15'],
                ['Livraison', 'SwissTransfer sécurisé'],
            ],
            'pricesIntro' => 'Trois formules selon le nombre d’animaux et le temps nécessaire pour qu’ils soient à l’aise.',
            'cta'        => 'Demander un devis',
            'cards'      => [
                [
                    'h' => 'Essentiel', 'dur' => 'Séance de 30 min',
                    'amount' => '165', 'unit' => 'CHF dès',
                    'li' => ['8 photos retouchées', 'Intérieur ou extérieur', 'Livraison privée sécurisée'],
                    'prefill' => 'Formule Essentiel animaux (30 min, 8 photos), dès 165 CHF.',
                ],
                [
                    'h' => 'Duo / Fratrie', 'dur' => 'Séance de 45 min',
                    'amount' => '225', 'unit' => 'CHF dès',
                    'li' => ['12 photos retouchées', '2 animaux, ou animal et propriétaire', 'Livraison privée sécurisée'],
                    'prefill' => 'Formule Duo / Fratrie animaux (45 min, 12 photos), dès 225 CHF.',
                ],
                [
                    'h' => 'Balade extérieure', 'dur' => 'Séance de 1 h',
                    'amount' => '295', 'unit' => 'CHF dès',
                    'li' => ['15 photos retouchées', 'En mouvement, plusieurs lieux', 'Livraison privée sécurisée'],
                    'prefill' => 'Formule Balade extérieure animaux (1 h, 15 photos), dès 295 CHF.',
                ],
            ],
            'note'       => 'Chaque séance comprend la sélection, la retouche professionnelle et la livraison privée sécurisée via SwissTransfer.',
            'disclaimer' => 'Tarifs de base, hors déplacement au-delà de 20 km. Devis personnalisé gratuit sous 48h. TVA non applicable.',
        ],

        'reportages' => [
            'nav'        => 'Reportages',
            'kicker'     => 'Reportages',
            'title'      => 'Un reportage à la hauteur de votre événement.',
            'metaTitle'  => 'Reportages',
            'metaDesc'   => 'Reportage photo pour mariages, concerts, anniversaires ou événements associatifs. Tri, retouche, livraison privée sécurisée et téléchargement HD inclus.',
            'lead'       => 'Mariage, concert, anniversaire, événement associatif : la nature de l’événement ne change pas la formule, seuls les exemples diffèrent. Ce qui compte, c’est la durée de couverture et le soin apporté au résultat. Je me déplace directement sur le lieu de votre événement.',
            'from'       => 'dès 390 CHF',
            'summary'    => 'Mariage, concert, anniversaire, événement associatif',
            'carousel'   => ['key' => 'reportages', 'category' => 'Evenements', 'label' => 'Reportages'],
            'facts'      => [
                ['Lieu', 'Sur le lieu de votre événement'],
                ['Couverture', '2 h, 4 h, 6 h ou journée'],
                ['Inclus', 'Tri, retouche, téléchargement HD'],
                ['Livraison', 'SwissTransfer sécurisé'],
            ],
            'pricesIntro' => 'La nature de l’événement ne change pas la formule : c’est la durée de couverture qui compte.',
            'cta'        => 'Demander un devis',
            'cards'      => [
                [
                    'h' => 'Essentiel', 'dur' => 'Couverture de 2 h',
                    'amount' => '390', 'unit' => 'CHF dès',
                    'li' => ['Tri et sélection des meilleures images', 'Retouche professionnelle', 'Livraison privée sécurisée', 'Téléchargement HD'],
                    'prefill' => 'Formule Essentiel (2 h de couverture), dès 390 CHF.',
                ],
                [
                    'h' => 'Demi-journée', 'dur' => 'Couverture de 4 h',
                    'amount' => '690', 'unit' => 'CHF dès',
                    'li' => ['Tri et sélection des meilleures images', 'Retouche professionnelle', 'Livraison privée sécurisée', 'Téléchargement HD'],
                    'prefill' => 'Formule Demi-journée (4 h de couverture), dès 690 CHF.',
                ],
                [
                    'h' => 'Étendu', 'dur' => 'Couverture de 6 h',
                    'amount' => '990', 'unit' => 'CHF dès',
                    'li' => ['Tri et sélection des meilleures images', 'Retouche professionnelle', 'Livraison privée sécurisée', 'Téléchargement HD'],
                    'prefill' => 'Formule Étendu (6 h de couverture), dès 990 CHF.',
                ],
            ],
            'highlight'  => [
                'h' => 'Mariage, journée complète',
                'p' => 'Des préparatifs à la soirée : une couverture continue pensée sur mesure pour votre mariage. Formule construite avec vous selon le déroulé de la journée.',
                'b' => 'Demander un devis personnalisé',
                'prefill' => 'Mariage journée complète (préparatifs à la soirée), devis personnalisé.',
            ],
            'disclaimer' => 'Tarifs de base, hors déplacement au-delà de 20 km. Devis personnalisé gratuit sous 48h. TVA non applicable.',
        ],

        'professionnels' => [
            'nav'        => 'Professionnels',
            'kicker'     => 'Professionnels',
            'title'      => 'Des images qui représentent votre entreprise.',
            'metaTitle'  => 'Professionnels',
            'metaDesc'   => 'Portraits d’équipe, images de vos locaux et contenu de communication, avec licence commerciale incluse. Séance dans vos locaux.',
            'lead'       => 'Portraits d’équipe, images de vos locaux, contenu pour votre communication : un résultat prêt à l’emploi, avec les droits d’utilisation qui vont avec. La séance se déroule directement dans vos locaux.',
            'from'       => 'dès 490 CHF',
            'summary'    => 'Artisan, PME, corporate multi-sites',
            'carousel'   => ['key' => 'professionnels', 'category' => 'Commandes professionnelles', 'label' => 'Professionnels'],
            'facts'      => [
                ['Lieu', 'Dans vos locaux'],
                ['Photos retouchées', '15, 25 ou sur mesure'],
                ['Droits', 'Licence commerciale 2 ans incluse'],
                ['Livraison', 'SwissTransfer sécurisé'],
            ],
            'pricesIntro' => 'Trois formules selon la taille de votre entreprise et le volume d’images dont vous avez besoin.',
            'cta'        => 'Demander un devis',
            'cards'      => [
                [
                    'h' => 'Artisan', 'dur' => 'Dans vos locaux',
                    'amount' => '490', 'unit' => 'CHF dès',
                    'li' => ['15 photos retouchées', 'Portrait professionnel', 'Images d’atelier', 'Photos d’équipe'],
                    'badge' => 'Licence commerciale Web &amp; réseaux sociaux (2 ans) incluse',
                    'prefill' => 'Formule Artisan (portrait, atelier, équipe, 15 photos), dès 490 CHF.',
                ],
                [
                    'h' => 'PME', 'dur' => 'Dans vos locaux',
                    'amount' => '890', 'unit' => 'CHF dès',
                    'li' => ['25 photos retouchées', 'Portraits des collaborateurs', 'Images des locaux', 'Contenu pour votre communication'],
                    'badge' => 'Licence commerciale Web &amp; réseaux sociaux (2 ans) incluse',
                    'prefill' => 'Formule PME (collaborateurs, locaux, communication, 25 photos), dès 890 CHF.',
                ],
                [
                    'h' => 'Corporate / multi-sites', 'dur' => 'Plusieurs sites ou équipes',
                    'amount' => 'Sur devis', 'quote' => true,
                    'li' => ['Plusieurs sites ou équipes', 'Coordination sur mesure', 'Contenu de communication complet'],
                    'badge' => 'Licence commerciale Web &amp; réseaux sociaux (2 ans) incluse',
                    'prefill' => 'Corporate / multi-sites, devis personnalisé.',
                ],
            ],
            'note'       => 'Chaque formule inclut le tri, la retouche professionnelle et la livraison privée sécurisée via SwissTransfer.',
            'disclaimer' => 'Tarifs de base, hors déplacement au-delà de 20 km. Devis personnalisé gratuit sous 48h. TVA non applicable.',
        ],
    ];
}
