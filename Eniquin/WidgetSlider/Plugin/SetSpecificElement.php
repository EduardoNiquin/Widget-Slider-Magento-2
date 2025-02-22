<?php

declare(strict_types=1);

namespace Eniquin\WidgetSlider\Plugin;

use Magento\Widget\Model\Config\Converter;

/**
 * Class SetSpecificElement
 *
 * Set the parameter's "type" based on the data coming from the helper_block
 */
class SetSpecificElement
{
    /**
     * After converting the widget configuration, we modify the type
     */
    public function afterConvert(Converter $subject, array $result, $source): array
    {
        foreach ($result as &$widget) {
            if (!isset($widget['parameters'])) {
                continue;
            }
            foreach ($widget['parameters'] as &$parameter) {
                if (!isset($parameter['helper_block'])) {
                    continue;
                }
                if (isset($parameter['helper_block']['data']['element'])) {
                    $parameter['type'] = $parameter['helper_block']['data']['element'];
                }
            }
        }
        return $result;
    }
}
