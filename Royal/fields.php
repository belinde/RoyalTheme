<?php
namespace Royal\Fields;

use Royal\Fields\AbstractField as _;

return [
	( new Select( 'status' ) )
		->setValues( [
			'disponibile' => 'disponibile sul mercato',
			'trattativa'  => 'in trattativa',
			'terminato'   => 'venduto o piazzato'
		] )
		->setHelp( "Determina se l'annuncio è visibile o meno sul sito" )
		->setInternal()
	,
	( new Text( 'proprietario' ) )
		->setInternal()
	,
	( new Integer( 'prezzo' ) )
		->setAppend( '€' )
		->setEmpty( 'trattativa riservata' )
		->setSearch( _::SEARCH_RANGE )
		->setInternal()
	,
	( new Integer( 'vani' ) )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Integer( 'superficie' ) )
		->setAppend( 'm&sup2;' )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Select( 'condizione' ) )
		->setValues( [
			'da_ristrutturare' => 'da ristrutturare',
			'ristrutturato'    => 'ristrutturato',
			'buono'            => 'buono',
			'nuovo'            => 'nuovo'
		] )
	,
	( new Integer( 'bagni', "Numero bagni" ) )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Integer( 'balconi' ) )
	,
	( new Integer( 'terrazzi' ) )
	,
	( new Select( 'riscaldamento' ) )
		->setValues( [
			'assente'      => 'assente',
			'condominio'   => 'condominiale',
			'indipendente' => 'termoautonomo'
		] )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Select( 'condizionatore', "Aria condizionata" ) )
		->setValues( [
			'assente'     => 'assente',
			'predisposto' => 'con predisposizione',
			'presente'    => 'presente'
		] )
		->setSearch( _::SEARCH_EXACT )
	,
	( new Boolean( 'ascensore' ) )
		->setSearch( _::SEARCH_EXACT )
	,
	( new Boolean( 'box', "Box auto" ) )
		->setSearch( _::SEARCH_EXACT )
	,
	( new Select( 'posteggio', "Posto auto" ) )
		->setValues( [
			'coperto'  => 'coperto',
			'scoperto' => 'scoperto'
		] )
	,
	( new LongText( 'indirizzo', "Indirizzo completo" ) )
];