<?php
namespace Royal\Fields;

use Royal\Fields\AbstractField as _;

return [
	( new Integer( 'prezzo' ) )
		->setAppend( '€' )
		->setEmpty( 'trattativa riservata' )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Integer( 'vani' ) )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Integer( 'superficie' ) )
		->setAppend( 'm&sup2;' )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Text( 'stato' ) )
		->setSearch( _::SEARCH_DISABLED )
	,
	( new Integer( 'bagni', "Numero bagni" ) )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Integer( 'balconi' ) )
		->setEmpty( 'nessuno' )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Integer( 'terrazzi' ) )
		->setEmpty( 'nessuno' )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Select( 'riscaldamento' ) )
		->setValues( [
			'assente'      => 'Assente',
			'condominio'   => 'Condominiale',
			'indipendente' => 'Termoautonomo'
		] )
		->setSearch( _::SEARCH_RANGE )
	,
	( new Text( 'condizionatore', "Aria condizionata" ) )
		->setSearch( _::SEARCH_DISABLED )
	,
	( new Boolean( 'ascensore' ) )
		->setSearch( _::SEARCH_EXACT )
	,
	( new Text( 'posteggio', "Posto auto" ) )
		->setSearch( _::SEARCH_DISABLED )
	,
	( new LongText( 'indirizzo', "Indirizzo completo" ) )
		->setSearch( _::SEARCH_DISABLED )
];