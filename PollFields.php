<?php
abstract class PollField
{
	function __construct( SimpleXMLElement &$tree )
	{
		$this->setTitle( $tree['title'] );
		$this->templateArray['idField'] = PollField::$fieldID++;

		$this->parse($tree);
	}

	public function setTitle( $title )
	{
		$this->title = $title;
		$this->templateArray['title'] = (string) $this->title;
	}
	
	public function getTemplateVars()
	{
		return $this->templateArray;
	}

	abstract protected function parse( SimpleXMLElement &$tree );

	protected $title = "";
	protected $templateArray;

	static protected $fieldID = 0;
}

abstract class SelectField extends PollField
{
	public function __construct(	SimpleXMLElement &$tree )
	{
		parent::__construct( $tree );		
	}

	protected function addChoice( $choiceName, $choiceID = NULL )
	{
		if ( is_null( $choiceID ) )
			$choiceID = $choiceName;

		$this->choices[] = array('label' => $choiceName, 'idOption' => $this->transformStringToID( $choiceID ) );
	}

	protected function transformStringToID( $id )
	{
		$id = str_replace('', '-', strtolower( $id ) );
   		return preg_replace('/[^A-Za-z0-9\-]/', '', $id );
	}

	protected $choices = array();
}

class ChoiceField extends SelectField
{
	public function __construct(	SimpleXMLElement &$tree )
	{
		if ( $tree->getName() != 'choice' )
			throw new Exception('You have to pass a SimpleXMLElement with \'choice\' as root element ');

		parent::__construct( $tree );

		$this->templateArray['questionType'] = 'choice';
	}

	protected function setAllowMultiChoice( $allow )
	{
		$this->allowMultiChoice = (bool) $allow;
	}

	protected function parse( SimpleXMLElement &$tree )
	{
		if ( isset( $tree['allowMultiple'] ) )
		{
			$this->setAllowMultiChoice( ( (string) $tree['allowMultiple'] ) == 'true' );
		}

		$this->templateArray['multipleChoice'] = $this->allowMultiChoice;

		foreach ( $tree->children() as $element )
		{
			if ( !isset( $element['idOption'] ) )
				$this->addChoice( (string) $element );
			else
				$this->addChoice( (string) $element, (string) $element['idOption'] );			
		}

		$this->templateArray['elements'] = $this->choices;
	}
	
	private $allowMultiChoice = false;
}

class RatingField extends SelectField
{
	public function __construct( SimpleXMLElement &$tree )
	{
		if ( $tree->getName() != 'rating' )
			throw new Exception('You have to pass a SimpleXMLElement with \'rating\' as root element ');

		parent::__construct( $tree );

		$this->templateArray['questionType'] = 'rating';
	}

	protected function fillChoices( $minRating = 1, $maxRating = 5, $numberOfOptions = 5 )
	{
		$increment = (float) ($maxRating - $minRating) / ( $numberOfOptions - 1 );

		for( $i = 0; $i < $numberOfOptions; $i++ )
		{
			$this->addChoice( ( (string) ($minRating + $increment * $i) ) );
		}
	}

	protected function parse( SimpleXMLElement &$tree )
	{
		$minRating = (isset($tree['minValue'])) ? (int) $tree['minValue'] : 1;
		$maxRating = (isset($tree['maxValue'])) ? (int) $tree['maxValue'] : 5;
		$numberOfOptions = (isset($tree['numberOfOptions'])) ? (int) $tree['numberOfOptions'] : 5;

		$this->fillChoices( $minRating, $maxRating, $numberOfOptions );

		$this->templateArray['elements'] = $this->choices;
	}
}