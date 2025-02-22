# Eniquin_WidgetSlider

This module for **Magento 2** introduces a **new type of Widget** that implements a **slider/carousel** using [SwiperJS](https://swiperjs.com/).
Thanks to **Eniquin_WidgetSlider**, you can create dynamic *slides* from the Magento Admin, where each slide can contain:
- **Images in two formats**: desktop version (*desktop*) and mobile version (*mobile*).
- **Action buttons**: up to **three buttons per slide**, each with its respective text and URL.
- **Advanced settings**: transition options, number of visible slides, autoplay, pagination, navigation arrows and more.

> 🚀 **Module Objective:**
> In addition to being fully functional, the code in this module is intended as an **educational resource** to learn how to:
> - Create a **dynamic widget** in Magento 2.
> - Implement fields that allow you to dynamically add content (*Add Slide*, *Remove Slide*).
> - **Integrate external libraries** such as SwiperJS in Magento.
> - Apply **advanced configurations** with XML, blocks, and custom templates.

---

# 📌 Index

1. [Introduction](#eniquin_widgetslider)
2. [📥 Installation](#installation)
3. [📂 Module Structure](#module-structure)
4. [⚙️ Widget Configuration in Magento](#widget-configuration-in-magento)
5. [🎨 Rendering the Slider in the Frontend](#rendering-the-slider-in-the-frontend)
6. [💡 Technical Explanation of Files](#technical-explanation-of-files)
7. [🛠️ Customization Options](#customization-options)
8. [📚 How to Insert the Widget in a [#how-to-insert-widget-on-a-page]
9. [🔄 Contributions](#contributions)
10. [📜 License](#license)

---

# installation

To install the module in **Magento 2**, follow these steps:

1. **Download or clone** this repository to the `app/code/Eniquin/WidgetSlider` folder inside your Magento installation:
```bash
git clone https://github.com/yourrepository/Eniquin_WidgetSlider.git app/code/Eniquin/WidgetSlider
```

2. **Enable the module** by running:
```bash
bin/magento module:enable Eniquin_WidgetSlider
```

3. **Run the database update**:
```bash
bin/magento setup:upgrade
```

4. **Compile Magento code** (required in production):
```bash
bin/magento setup:di:compile
```

5. **Clear cache** to apply changes:
```bash
bin/magento cache:flush
```

6. **(Optional)** If you are in production mode, you can deploy the static files:
```bash
bin/magento setup:static-content:deploy -f
```

---

# module-structure

```
app/code/Eniquin/WidgetSlider
├── Block
│ ├── Adminhtml
│ │ └── Widget
│ │ ├── dynamic.php
│ │ ├── Heading.php
│ │ ├── Slide.php
│ │ └── Text.php
│ └── Widget
│ └── SlideWidget.php
├── etc.
│ ├── di.xml
│ ├── module.xml
│ └── widget.xml
├──Plugin
│ └── SetSpecificElement.php
├── registration.php
├── view
│ ├── adminhtml
│ │ ├── layout
│ │ │ └── default.xml
│ │ ├── templates
│ │ │ ├── add.phtml
│ │ │ ├── add_slide.phtml
│ │ │ ├── heading_appearance.phtml
│ │ │ ├── heading_desktop.phtml
│ │ │ ├── heading_mobile.phtml
│ │ │ ├── slide.phtml
│ │ │ └── text.phtml
│ │ ├── website
│ │ │ └── css
│ │ │ └── widget.css
│ ├── frontend
│ │ ├── layout
│ │ │ └── default.xml
│ │ ├── templates
│ │ │ ├── dynamic-text-widget.phtml
│ │ │ ├── include_swiper_js.phtml
│ │ │ └── slide-widget.phtml
│ │ ├── web
│ │ │ ├── css
│ │ │ │ ├── slider.css
│ │ │ │ └── swiper.min.css
│ │ │ ├── js
│ │ │ │ └── swiper.min.js
```

### 📌 Quick explanation:
- **`Block/Adminhtml/Widget/`** → Contains the blocks that handle the interface in the **Admin Magento**.
- **`Block/Widget/SlideWidget.php`** → Contains the widget logic on the frontend.
- **`etc/widget.xml`** → Defines the widget structure in the admin.
- **`view/adminhtml/templates/`** → `.phtml` files to render the widget fields in the admin panel.
- **`view/frontend/templates/`** → `.phtml` files that render the slider in the store.
- **`view/frontend/web/`** → **CSS and JS** files for the slider layout and functionality.
- **`Plugin/SetSpecificElement.php`** → Plugin that modifies how Magento handles certain widget parameters.

---

# widget-configuration-in-magento

The widget for this module is defined in the `etc/widget.xml` file, which is where you set:
- **The widget parameters** (number of slides, autoplay, arrows, pagination, effects, etc.).
- **The class that renders the widget** on the frontend.
- **Custom blocks** to handle dynamic content in the admin.

Magento detects this file automatically when the module is enabled, and the widget appears under **Content > Widgets** in the backend.

---

## 📝 Widget definition in `etc/widget.xml`

The `widget.xml` file defines the widget **"Eniquin Slider Widget"**, indicating its configuration:

```xml
<widgets xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
 xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Widget:etc/widget.xsd">

 <widget id="eniquin_widgetslider_slide_widget"
 class="Eniquin\WidgetSlider\Block\Widget\SlideWidget">
 <label translate="true">Eniquin Slider Widget</label>
 <description translate="true">Widget to display slides using SwiperJS.</description>
 <parameters>

 <!-- Dynamic slides parameter -->
 <parameter name="slides" xsi:type="block" visible="true" sort_order="10">
 <label translate="true">Slides</label>
 <block class="Eniquin\WidgetSlider\Block\Adminhtml\Widget\Dynamic">
 <data>
 <item name="label" xsi:type="string">Slide</item>
 <item name="element" xsi:type="string">Eniquin\WidgetSlider\Block\Adminhtml\Widget\Slide</item>
 <item name="label_add_row" xsi:type="string">Add Slide</item>
 <item name="label_remove_row" xsi:type="string">Remove Slide</item>
 </data>
 </block>
 </parameter>

 <!-- General settings -->
 <parameter name="slides_per_view" xsi:type="text" visible="true" sort_order="20">
 <label translate="true">Slides per View</label>
 <value>1</value>
 </parameter>

 <parameter name="effect" xsi:type="select" visible="true" sort_order="30">
 <label translate="true">Slider Effect</label>
 <options>
 <option name="slide" value="slide" selected="true"><label translate="true">Slide</label></option>
 <option name="fade" value="fade"><label translate="true">Fade</label></option>
 <option name="cube" value="cube"><label translate="true">Cube</label></option>
<option name="coverflow" value="coverflow"><label translate="true">Coverflow</label></option>
<option name="flip" value="flip"><label translate="true">Flip</label></option>
</options>
</parameter>

</parameters>
</widget>
</widgets>
```

> 🔹 **Explanation:**
> - The widget is **identified as `eniquin_widgetslider_slide_widget`** and is associated with the `SlideWidget.php` class.
> - The `<parameter name="slides" xsi:type="block">` parameter is used to allow **dynamic slides**, using the `Dynamic.php` block.
> - There are multiple parameters to configure the appearance of the slider on the frontend.

---

## 🎛 Creating Dynamic Blocks in Admin

To allow users to add **slides dynamically** from the backend, we use blocks in `Block/Adminhtml/Widget`:

- **`Dynamic.php`** → Defines a repeatable field that stores slides.
- **`Slide.php`** → Renders the interface of a slide (desktop image, mobile, buttons).
- **`Text.php`** → Allows you to add dynamic text.

The `Dynamic.php` block is associated with the `slides` parameter in `widget.xml`, and in turn, within `Dynamic.php` it is specified that each element is a `Slide.php`.

---

## 📂 Templates in `view/adminhtml/templates`

These `.phtml` files are responsible for the **interface in the backend**. Some examples:

- **`slide.phtml`** → Defines the interface of each slide.
- **`add_slide.phtml`** → *"Add Slide"* button that allows adding more slides.
- **`text.phtml`** → Handles dynamic text inputs.
- **`heading_mobile.phtml`**, `heading_desktop.phtml`, etc. → These are titles within the widget to better organize the interface.

When the user **adds or removes slides**, the values ​​are automatically updated in a `<input hidden>`, which is then sent and stored by Magento.

---

# rendering-the-slider-on-the-frontend

The slider is displayed in the store using the template `view/frontend/templates/slide-widget.phtml`.

---

## 🖼 Code in `slide-widget.phtml`

This file is **in charge of generating the HTML for the slider**. It takes the data stored in the widget and converts it into `<div class="swiper-slide">`.

```php
<?php
$slides = $block->getSlides();
$widgetUid = $block->getWidgetUid();
?>

<div class="eniquin-slider-container <?= $widgetUid ?>">
 <div class="swiper <?= $widgetUid ?>-swiper">
 <div class="swiper-wrapper">
 <?php foreach ($slides as $slide): ?>
 <div class="swiper-slide">
 <img src="<?= $block->escapeHtml($slide['desktop']) ?>" alt="Slide">
 </div>
 <?php endforeach; ?>
 </div>
 <div class="swiper-pagination"></div>
 <div class="swiper-button-next"></div>
 <div class="swiper-button-prev"></div>
 </div>
</div>

<script>
require(['jquery', 'swiper'], function($, Swiper) {
 new Swiper('.<?= $widgetUid ?>-swiper', {
 loop: true,
 pagination: { el: '.swiper-pagination', clickable: true },
 navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' }
 });
});
</script>
```

- Gets the slides from `getSlides()` and generates a `<div class="swiper-slide">` for each one.
- Uses **SwiperJS** to initialize the slider.

---

## ⚙️ SwiperJS Integration

Magento loads the SwiperJS library from `view/frontend/web/js/swiper.min.js`.
We also have `view/frontend/web/css/slider.css`, which defines the styles of the slider.

> **Example of advanced options in SwiperJS:**
> ```js
> new Swiper('.<?= $widgetUid ?>-swiper', {
> loop: true,
> autoplay: { delay: 3000 },
> effect: 'fade',
> pagination: { el: '.swiper-pagination', type: 'bullets' },
> });
> ```

With this, the slider is automatically configured according to the parameters saved in the widget.

---

## 🎨 Advanced Settings

From the widget in the backend, you can change:
- Number of visible slides.
- Spacing between slides.
- Transition effects (fade, cube, flip, etc.).
- Navigation arrows.
- Pagination with bullets, fraction or progress bar.
- Autoplay with custom delay.

All of these settings are reflected in `slide-widget.phtml`, modifying SwiperJS options based on what the user has defined.

---

# technical-explanation-of-files

In this section, we will explain **how the key files of the module work**, including the most important blocks, plugins and templates.

---

## 📌 `Block/Adminhtml/Widget/`

### 📄 `Dynamic.php`
This file allows for the creation of **dynamic fields** in the admin.

- Used in `widget.xml` as `<parameter name="slides" xsi:type="block">`.
- Allows for adding multiple slides in a repeatable manner.
- Injects the **"Add Slide"** and **"Remove Slide"** tags into the backend.

```php
class Dynamic extends Widget
{
public function prepareElementHtml(AbstractElement $element): AbstractElement
{
$element->setData('label', $this->getData('label'));
return $element;
}
}
```

### 📄 `Slide.php`
This file **renders each slide** within the manager.
Each slide has **images (desktop, mobile) and buttons**.

```php
public function render(AbstractElement $element)
{
 $html = '<fieldset class="dynamic fieldset admin__fieldset">';
 $html .= '<legend>' . $element->getLabel() . '</legend>';
 $html .= '<div class="content">';
 $html .= $this->getSlideRowHtml($element);
 $html .= '</div></fieldset>';
 return $html;
}
```

---

## 📌 `Block/Widget/SlideWidget.php`

This file controls how the slider is **rendered on the frontend**.

### 🔹 Main methods:
- `getSlides()`: Retrieves the slides from the saved data.
- `getEffect()`, `showArrows()`, etc.: Gets the configuration defined in the widget.
- `getWidgetUid()`: Generates a **unique UID** to avoid conflicts between sliders.

```php
public function getSlides(): array
{
$rawSlides = (string)$this->getData('slides');
$slideRows = explode(',', $rawSlides);
$slides = [];

foreach ($slideRows as $row) {
 $parts = explode('|', $row);
 $slides[] = [
 'desktop' => $parts[0] ?? '',
 'mobile' => $parts[1] ?? '',
 'buttons' => [
 ['text' => $parts[2] ?? '', 'url' => $parts[3] ?? ''],
 ['text' => $parts[4] ?? '', 'url' => $parts[5] ?? ''],
 ['text' => $parts[6] ?? '', 'url' => $parts[7] ?? ''],
 ],
 ];
 }
 return $slides;
}
```

> 🔹 **Explanation:**
> - Slide information is stored in a **string separated by commas and pipes (`|`)**.
> - `getSlides()` breaks these values ​​down into a **structured array** which is then used in `slide-widget.phtml`.

---

## 📌 `Plugin/SetSpecificElement.php`

This plugin modifies how Magento **processes widget parameters**.

```php
class SetSpecificElement
{
 public function afterConvert(Converter $subject, array $result, $source): array
 {
 foreach ($result as &$widget) {
 if (!isset($widget['parameters'])) continue;

 foreach ($widget['parameters'] as &$parameter) {
 if (isset($parameter['helper_block']['data']['element'])) {
 $parameter['type'] = $parameter['helper_block']['data']['element'];
 }
 }
 }
 return $result;
 }
}
```

> 🔹 **What is it for?**
> - It is responsible for assigning **the correct type to each parameter of the widget**.
> - It prevents errors when Magento interprets dynamic data.

---

# customization-options

The widget offers multiple options to **customize the behavior of the slider**.
All these options are configured in the backend and applied in `slide-widget.phtml`.

### 🎨 Available Options

| Option | Description | Values ​​|
|--------|------------|---------|
| `slides_per_view` | Number of slides visible at the same time | 1, 2, 3, etc. |
| `slides_per_group` | Number of slides that advance in each transition | 1, 2, 3, etc. |
| `effect` | Transition effect between slides | `slide`, `fade`, `cube`, `coverflow`, `flip` |
| `direction` | Slider direction | `horizontal`, `vertical` |
| `autoplay` | Enable/disable autoplay | `true`, `false` |
| `autoplay_delay` | Time between slide changes (in ms) | `1000`, `3000`, `5000`, etc. |
| `show_arrows` | Show navigation arrows | `true`, `false` |
| `show_pagination` | Show pagination (bullets) | `true`, `false` |
| `color_primary` | Primary slider color | Hexadecimal (`#007aff`) |
| `color_secondary` | Secondary color (hover, active) | Hexadecimal (`#ff0000`) |

### ✏️ Backend Customization Example

In the widget you can change **the transition effect** and activate **autoplay**:

```xml
<parameter name="effect" xsi:type="select" visible="true">
 <label translate="true">Slider Effect</label>
 <options>
 <option name="slide" value="slide" selected="true"><label translate="true">Slide</label></option>
 <option name="fade" value="fade"><label translate="true">Fade</label></option>
 </options>
</parameter>

<parameter name="enable_autoplay" xsi:type="select" visible="true">
 <label translate="true">Enable Autoplay?</label>
 <options>
 <option name="no" value="0" selected="true"><label translate="true">No</label></option>
<option name="yes" value="1"><label translate="true">Yes</label></option>
</options>
</parameter>
```

---

## 🖥️ Applying Personalization in `slide-widget.phtml`

The code in `slide-widget.phtml` reads these values ​​and applies them to the slider:

```js
var swiper = new Swiper('#slider-<?= $widgetUid ?>', {
loop: true,
slidesPerView: <?= $this->getSlidesPerView() ?>,
effect: '<?= $this->getEffect() ?>',
autoplay: (<?= $this->isAutoplayEnabled() ?>) ? { delay: <?= $this->getAutoplayDelay() ?> } : false,
});
```

> 🔹 **Explanation:**
> - The **user-defined** option is taken from the backend and used in SwiperJS.
> - If `autoplay` is enabled, the slider moves automatically.

---

## 🎨 Style Changes in `slider.css`

If you want to customize the appearance of the slider, you can modify `slider.css`:

```css
.eniquin-slider-container .swiper-slide {
background: linear-gradient(to right, #007aff, #ff0000);
border-radius: 10px;
}
```

> 🔹 **Tip:** Use `custom_class` on the widget to add custom classes without modifying the base CSS.

---

# how-to-insert-widget-on-a-page

Magento allows you to easily add widgets using the **Page Builder** or directly from the **content manager**.
Here's how to insert the widget in different locations.

---

## 🛠️ Method 1: Insert Widget into a Page or Block

1. **Go to the Magento admin panel.**
2. Go to **Content > Pages** or **Content > Blocks**, depending on where you want to add the slider.
3. **Edit** the page or block where you want to insert the slider.
4. In the content editor, click **Insert Widget**. 5. Select **"Eniquin Slider Widget"** from the widget list.
6. Configure the widget parameters to your liking (number of slides, autoplay, effects, etc.).
7. Save changes and view the page on the frontend.

> 🔹 **Tip:** This method is ideal if you want to place the slider inside a CMS block, on the homepage, or on custom pages.

---

## 📌 Method 2: Adding the Widget to an XML Layout

If you want to insert the slider into a **specific layout**, you can do so in an XML layout file.

Example: Adding the widget to the header (`default.xml`):

```xml
<referenceContainer name="header.container">
<block class="Magento\Widget\Block\Block" name="custom.slider" after="-">
<arguments>
<argument name="block_id" xsi:type="string">eniquin_widgetslider_slide_widget</argument>
</arguments>
</block>
</referenceContainer>
```

> 🔹 **Tip:** Use this method if you need the slider to appear **on all pages** or in a specific section of the layout.

---

## ✏️ Method 3: Inserting the Widget Manually in a `.phtml`

If you need to insert the slider **inside a custom template**, you can do it manually with the following code:

```php
<?php
echo $this->getLayout()
->createBlock('Magento\Widget\Block\Block')
->setData(['type' => 'Eniquin_WidgetSlider/Widget/SlideWidget'])
->toHtml();
?>
```

> 🔹 **Tip:** Useful if you need to display the slider inside custom `.phtml` files in your theme.

---

# contributions

This module is **open-source** and any contributions are welcome. If you want to improve the code, fix bugs, or add new features, follow these steps:

## 🚀 How to Contribute

1. **Fork** this repository.
2. Create a new branch with a clear description of your change:
```bash
git checkout -b feature/new-feature
```
3. Make the necessary modifications and test the changes in Magento.
4. **Commit** with a clear description:
```bash
git commit -m "Added autoplay option to slider"
```
5. **Upload the changes to your fork** and create a Pull Request in the main repository.

## 📌 Rules for Contributing

- If you add a new feature, **document the code**.
- Make sure the module still works correctly before submitting changes.
- If you fix a bug, **describe the problem and how you solved it**.

---

# license

This project is licensed under the **MIT** license, which means you can **use, modify and distribute** the code freely, as long as you include the original license.

## 📄 MIT License

```
MIT License

Copyright (c) 2025

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

You can use this code for your own projects, learn from it or improve it.
If you make changes and improvements, it would be great if you shared them with the community! 🚀