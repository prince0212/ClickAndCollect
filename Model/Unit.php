<?php
declare(strict_types=1);
 
namespace Deloitte\ClickAndCollect\Model;

class Unit
{
	
    public function toOptionArray(): array
    {
        return array(
            array(
                'value' => 'default',
                'label' => 'Kilometres',
            ),
            array(
                'value' => 'miles',
                'label' => 'Miles',
            )
        );
    }
    
}
