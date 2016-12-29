<?php
namespace Royal\Fields;

use Royal\Fields\AbstractField as _;

return [
	( new Integer( 'prezzo' ) )
		->setAppend( '€' )
		->setEmpty( 'trattativa riservata' )
		->setSearch( _::SEARCH_RANGE )
		->setInternal()
	,
	( new Select( 'status' ) )
		->setValues( [
			'disponibile' => 'Disponibile sul mercato',
			'trattativa'  => 'In trattativa',
			'terminato'   => 'Venduto o piazzato'
		] )
		->setHelp( "Determina se l'annuncio è visibile o meno sul sito" )
		->setInternal()
	,
	( new Integer( 'vani' ) )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Integer( 'superficie' ) )
		->setAppend( 'm&sup2;' )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Text( 'condizione' ) )
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
	( new Text( 'posteggio', "Posto auto" ) )
	,
	( new LongText( 'indirizzo', "Indirizzo completo" ) )
];