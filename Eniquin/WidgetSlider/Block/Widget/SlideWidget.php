<?php

namespace Eniquin\WidgetSlider\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

/**
 * Main Slider Block.
 */
class SlideWidget extends Template implements BlockInterface
{
    protected $_template = 'slide-widget.phtml';

    /** @var string UID to identify each slider in the frontend. */
    protected $widgetUid;

    protected function _beforeToHtml()
    {
        $this->widgetUid = uniqid('eniquin_slider_');
        return parent::_beforeToHtml();
    }

    /**
     * Returns the unique ID of this slider.
     */
    public function getWidgetUid(): string
    {
        return $this->widgetUid;
    }

    /**
     * Get url for action
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->_urlBuilder->getUrl($route, $params);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (!$this->getTemplate()) {
            $this->setTemplate('Eniquin_WidgetSlider::slide.phtml');
        }
        return $this;
    }

    /**
     * Returns an array with the slides (desktop image + mobile image + 3 buttons).
     */
    public function getSlides(): array
    {
        $rawSlides = (string)$this->getData('slides');
        if (!$rawSlides) {
            return [];
        }

        $slideRows = array_filter(
            explode(',', $rawSlides),
            fn($row) => trim($row) !== ''
        );

        $slides = [];
        foreach ($slideRows as $row) {
            $parts = explode('|', $row);
            $slides[] = [
                'desktop' => $parts[0] ?? '',
                'mobile'  => $parts[1] ?? '',
                'buttons' => [
                    ['text' => $parts[2] ?? '', 'url' => $parts[3] ?? ''],
                    ['text' => $parts[4] ?? '', 'url' => $parts[5] ?? ''],
                    ['text' => $parts[6] ?? '', 'url' => $parts[7] ?? ''],
                ],
            ];
        }
        return $slides;
    }

    /* ================== General Configuration ================== */

    public function getMobileBreakpoint(): int
    {
        return (int)($this->getData('mobile_breakpoint') ?: 768);
    }

    public function getSlidesPerView(): int
    {
        return (int)($this->getData('slides_per_view') ?: 1);
    }

    public function getSpaceBetween(): int
    {
        return (int)($this->getData('space_between') ?: 0);
    }

    public function getDirection(): string
    {
        return (string)($this->getData('direction') ?: 'horizontal');
    }

    public function getSliderHeight(): int
    {
        return (int)($this->getData('slider_height') ?: 600);
    }

    /* ================== Effects ================== */

    public function getEffect(): string
    {
        return (string)($this->getData('effect') ?: 'slide');
    }

    /**
     * Pagination type: "bullets", "fraction", "progressbar"
     */
    public function getPaginationType(): string
    {
        $valid = ['bullets', 'fraction', 'progressbar'];
        $type  = (string)$this->getData('pagination_type') ?: 'bullets';
        return in_array($type, $valid) ? $type : 'bullets';
    }

    /* ================== Appearance / Other ================== */

    public function getColorPrimary(): string
    {
        return (string)($this->getData('color_primary') ?: '#007aff');
    }

    public function getColorSecondary(): string
    {
        return (string)($this->getData('color_secondary') ?: '#ff0000');
    }

    public function showArrows(): bool
    {
        return (bool)$this->getData('show_arrows');
    }

    public function showPagination(): bool
    {
        return (bool)$this->getData('show_pagination');
    }

    public function isAutoplayEnabled(): bool
    {
        return (bool)$this->getData('enable_autoplay');
    }

    public function getAutoplayDelay(): int
    {
        return (int)($this->getData('autoplay_delay') ?: 3000);
    }

    public function getCustomClass(): string
    {
        return (string)($this->getData('custom_class') ?: '');
    }

    public function getSlidesPerColumn(): int
    {
        return (int)$this->getData('slides_per_column') ?: 1;
    }

    public function getSlidesPerGroup(): int
    {
        return (int)$this->getData('slides_per_group') ?: 1;
    }

    public function getSliderCssClass(): string
    {
        return (string)$this->getData('slider_css_class') ?: '';
    }
}
