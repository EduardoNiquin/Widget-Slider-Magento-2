<?php

namespace Eniquin\WidgetSlider\Block\Adminhtml\Widget;

use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\View\Element\Template;

/**
 * Custom class to handle headers in widgets.
 */
class Heading extends Template
{
    /**
     * Method necessary to avoid errors in administration.
     *
     * @param AbstractElement $element
     * @return $this
     */
    public function prepareElementHtml(AbstractElement $element)
    {
        // Returns the block without modifications.
        return $this;
    }
}
