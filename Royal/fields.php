<?php
namespace Royal\Fields;

use Royal\Fields\AbstractField as _;

return [
    (new Boolean('evidenza', "In evidenza"))
        ->setInternal()
    ,
    (new Select('status'))
        ->setValues([
            'disponibile' => 'disponibile sul mercato',
            'trattativa'  => 'in trattativa',
            'terminato'   => 'venduto o piazzato'
        ])
        ->setHelp("Determina se l'annuncio è visibile o meno sul sito")
        ->setInternal()
    ,
    (new Text('proprietario'))
        ->setInternal()
    ,
    (new Integer('prezzo'))
        ->setAppend('€')
        ->setEmpty('trattativa riservata')
        ->setSearch(_::SEARCH_RANGE)
        ->setInternal()
    ,
    (new Integer('vani'))
        ->setSearch(_::SEARCH_RANGE)
    ,
    (new Integer('superficie'))
        ->setAppend('m&sup2;')
        ->setSearch(_::SEARCH_RANGE)
    ,
    (new Select('condizione'))
        ->setValues([
            'da_ristrutturare' => 'da ristrutturare',
            'ristrutturato'    => 'ristrutturato',
            'buono'            => 'buono',
            'nuovo'            => 'nuovo'
        ])
    ,
    (new Integer('bagni', "Numero bagni"))
        ->setSearch(_::SEARCH_RANGE)
    ,
    (new Integer('balconi'))
    ,
    (new Integer('terrazzi'))
    ,
    (new Select('riscaldamento'))
        ->setValues([
            'assente'      => 'assente',
            'condominio'   => 'condominiale',
            'indipendente' => 'termoautonomo'
        ])
        ->setSearch(_::SEARCH_RANGE)
    ,
    (new Select('condizionatore', "Aria condizionata"))
        ->setValues([
            'assente'     => 'assente',
            'predisposto' => 'con predisposizione',
            'presente'    => 'presente'
        ])
        ->setSearch(_::SEARCH_EXACT)
    ,
    (new Boolean('ascensore'))
        ->setSearch(_::SEARCH_EXACT)
    ,
    (new Boolean('box', "Box auto"))
        ->setSearch(_::SEARCH_EXACT)
    ,
    (new Select('posteggio', "Posto auto"))
        ->setValues([
            'coperto'  => 'coperto',
            'scoperto' => 'scoperto'
        ])
    ,
    (new Select('tipoaffitto', "Tipo di affitto"))
        ->setValues([
            'residenziale' => 'residenziale',
            'turistico'    => 'turistico'
        ])
        ->setSearch(_::SEARCH_EXACT)
    ,
    (new Select('arredo', "Arredo"))
        ->setValues([
            'ammobiliato'    => 'ammobiliato',
            'nonammobiliato' => 'non ammobiliato'
        ])
        ->setSearch(_::SEARCH_EXACT)
    ,
    (new LongText('indirizzo', "Indirizzo completo")),
    (new Boolean('mostraindirizzo', "Mostra indirizzo"))
        ->setInternal(),
    (new Select('classeenergetica', "Classe Energetica (kWh/m²)"))
        ->setValues([
            'nonindicata'       => 'non indicata',
            'dl192'             => 'D.L. 192 del 19/8/2015',
            'dl_ap'             => 'A+',
            'dl_a'              => 'A',
            'dl_b'              => 'B',
            'dl_c'              => 'C',
            'dl_d'              => 'D',
            'dl_e'              => 'E',
            'dl_f'              => 'F',
            'dl_g'              => 'G',
            'dm26_6_2015'       => 'D.M. 26/6/2015',
            'dm_a4'             => 'A4',
            'dm_a3'             => 'A3',
            'dm_a2'             => 'A2',
            'dm_a1'             => 'A1',
            'dm_b'              => 'B',
            'dm_c'              => 'C',
            'dm_d'              => 'D',
            'dm_e'              => 'E',
            'dm_f'              => 'F',
            'dm_g'              => 'G',
            'nonclassificabile' => 'Non classificabile',
            'esente'            => 'Esente',
            'inrichiesta'       => 'In fase di richiesta',
        ])
        ->setSearch(_::SEARCH_EXACT)
    ,
    (new Text('prestazioneenergetica', "Indice di Prestazione Energetica (IPE)")),
];