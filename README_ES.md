# Eniquin_WidgetSlider

Este módulo para **Magento 2** introduce un **nuevo tipo de Widget** que implementa un **slider/carousel** utilizando [SwiperJS](https://swiperjs.com/).  
Gracias a **Eniquin_WidgetSlider**, puedes crear *slides* dinámicos desde el Administrador de Magento, donde cada slide puede contener:
- **Imágenes en dos formatos**: versión de escritorio (*desktop*) y versión móvil (*mobile*).
- **Botones de acción**: hasta **tres botones por slide**, cada uno con su respectivo texto y URL.
- **Configuraciones avanzadas**: opciones de transición, cantidad de slides visibles, autoplay, paginación, flechas de navegación y más.

> 🚀 **Objetivo del módulo:**  
> Además de ser completamente funcional, el código de este módulo está pensado como un **recurso educativo** para aprender a:  
> - Crear un **widget dinámico** en Magento 2.  
> - Implementar campos que permiten agregar contenido dinámicamente (*Add Slide*, *Remove Slide*).  
> - **Integrar librerías externas** como SwiperJS en Magento.  
> - Aplicar **configuraciones avanzadas** con XML, bloques y plantillas personalizadas.  

---

# 📌 Índice

1. [Introducción](#eniquin_widgetslider)  
2. [📥 Instalación](#instalación)  
3. [📂 Estructura del Módulo](#estructura-del-módulo)  
4. [⚙️ Configuración del Widget en Magento](#configuración-del-widget-en-magento)  
5. [🎨 Renderización del Slider en el Frontend](#renderización-del-slider-en-el-frontend)  
6. [💡 Explicación Técnica de Archivos](#explicación-técnica-de-archivos)  
7. [🛠️ Opciones de Personalización](#opciones-de-personalización)  
8. [📚 Cómo Insertar el Widget en una Página](#cómo-insertar-el-widget-en-una-página)  
9. [🔄 Contribuciones](#contribuciones)  
10. [📜 Licencia](#licencia)  


---

# instalación

Para instalar el módulo en **Magento 2**, sigue estos pasos:

1. **Descarga o clona** este repositorio en la carpeta `app/code/Eniquin/WidgetSlider` dentro de tu instalación de Magento:
   ```bash
   git clone https://github.com/turepositorio/Eniquin_WidgetSlider.git app/code/Eniquin/WidgetSlider
   ```

2. **Habilita el módulo** ejecutando:
   ```bash
   bin/magento module:enable Eniquin_WidgetSlider
   ```

3. **Ejecuta la actualización de la base de datos**:
   ```bash
   bin/magento setup:upgrade
   ```

4. **Compila el código de Magento** (requerido en producción):
   ```bash
   bin/magento setup:di:compile
   ```

5. **Limpia la caché** para aplicar los cambios:
   ```bash
   bin/magento cache:flush
   ```

6. **(Opcional)** Si estás en modo producción, puedes desplegar los archivos estáticos:
   ```bash
   bin/magento setup:static-content:deploy -f
   ```

---

# estructura-del-módulo

```
app/code/Eniquin/WidgetSlider
├── Block
│   ├── Adminhtml
│   │   └── Widget
│   │       ├── Dynamic.php
│   │       ├── Heading.php
│   │       ├── Slide.php
│   │       └── Text.php
│   └── Widget
│       └── SlideWidget.php
├── etc
│   ├── di.xml
│   ├── module.xml
│   └── widget.xml
├── Plugin
│   └── SetSpecificElement.php
├── registration.php
├── view
│   ├── adminhtml
│   │   ├── layout
│   │   │   └── default.xml
│   │   ├── templates
│   │   │   ├── add.phtml
│   │   │   ├── add_slide.phtml
│   │   │   ├── heading_appearance.phtml
│   │   │   ├── heading_desktop.phtml
│   │   │   ├── heading_mobile.phtml
│   │   │   ├── slide.phtml
│   │   │   └── text.phtml
│   │   ├── web
│   │   │   └── css
│   │   │       └── widget.css
│   ├── frontend
│   │   ├── layout
│   │   │   └── default.xml
│   │   ├── templates
│   │   │   ├── dynamic-text-widget.phtml
│   │   │   ├── include_swiper_js.phtml
│   │   │   └── slide-widget.phtml
│   │   ├── web
│   │   │   ├── css
│   │   │   │   ├── slider.css
│   │   │   │   └── swiper.min.css
│   │   │   ├── js
│   │   │   │   └── swiper.min.js
```

### 📌 Explicación rápida:
- **`Block/Adminhtml/Widget/`** → Contiene los bloques que manejan la interfaz en el **Administrador de Magento**.  
- **`Block/Widget/SlideWidget.php`** → Contiene la lógica del widget en el frontend.  
- **`etc/widget.xml`** → Define la estructura del widget en el administrador.  
- **`view/adminhtml/templates/`** → Archivos `.phtml` para renderizar los campos del widget en el panel de administración.  
- **`view/frontend/templates/`** → Archivos `.phtml` que renderizan el slider en la tienda.  
- **`view/frontend/web/`** → Archivos **CSS y JS** para el diseño y funcionalidad del slider.  
- **`Plugin/SetSpecificElement.php`** → Plugin que modifica cómo Magento maneja ciertos parámetros del widget.  

---

# configuración-del-widget-en-magento

El widget de este módulo está definido en el archivo `etc/widget.xml`, que es donde se establecen:
- **Los parámetros del widget** (cantidad de slides, autoplay, flechas, paginación, efectos, etc.).
- **La clase que renderiza el widget** en el frontend.
- **Bloques personalizados** para manejar contenido dinámico en el administrador.

Magento detecta este archivo automáticamente cuando el módulo está habilitado, y el widget aparece en **Content > Widgets** en el backend.

---

## 📝 Definición del Widget en `etc/widget.xml`

El archivo `widget.xml` define el widget **"Eniquin Slider Widget"**, indicando su configuración:

```xml
<widgets xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Widget:etc/widget.xsd">

    <widget id="eniquin_widgetslider_slide_widget"
            class="Eniquin\WidgetSlider\Block\Widget\SlideWidget">
        <label translate="true">Eniquin Slider Widget</label>
        <description translate="true">Widget to display slides using SwiperJS.</description>
        <parameters>

            <!-- Parámetro de slides dinámicos -->
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

            <!-- Configuraciones generales -->
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

> 🔹 **Explicación:**  
> - El widget se **identifica como `eniquin_widgetslider_slide_widget`** y se asocia con la clase `SlideWidget.php`.
> - Se usa el parámetro `<parameter name="slides" xsi:type="block">` para permitir **slides dinámicos**, utilizando el bloque `Dynamic.php`.
> - Hay múltiples parámetros para configurar la apariencia del slider en el frontend.

---

## 🎛 Creación de Bloques Dinámicos en Admin

Para que los usuarios puedan añadir **slides dinámicamente** desde el backend, usamos bloques en `Block/Adminhtml/Widget`:

- **`Dynamic.php`** → Define un campo repetible que almacena slides.  
- **`Slide.php`** → Renderiza la interfaz de un slide (imagen desktop, mobile, botones).  
- **`Text.php`** → Permite añadir texto dinámico.

El bloque `Dynamic.php` se asocia con el parámetro `slides` en `widget.xml`, y a su vez, dentro de `Dynamic.php` se especifica que cada elemento es un `Slide.php`.

---

## 📂 Templates en `view/adminhtml/templates`

Estos archivos `.phtml` se encargan de la **interfaz en el backend**. Algunos ejemplos:

- **`slide.phtml`** → Define la interfaz de cada slide.
- **`add_slide.phtml`** → Botón *"Add Slide"* que permite añadir más slides.
- **`text.phtml`** → Maneja inputs de texto dinámico.
- **`heading_mobile.phtml`**, `heading_desktop.phtml`, etc. → Son títulos dentro del widget para organizar mejor la interfaz.

Cuando el usuario **añade o elimina slides**, los valores se actualizan automáticamente en un `<input hidden>`, que luego es enviado y almacenado por Magento.

---

# renderización-del-slider-en-el-frontend

El slider se muestra en la tienda mediante el template `view/frontend/templates/slide-widget.phtml`.

---

## 🖼 Código en `slide-widget.phtml`

Este archivo es el **encargado de generar el HTML del slider**. Toma los datos guardados en el widget y los convierte en `<div class="swiper-slide">`.

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

- Obtiene los slides de `getSlides()` y genera un `<div class="swiper-slide">` por cada uno.
- Usa **SwiperJS** para inicializar el slider.

---

## ⚙️ Integración con SwiperJS

Magento carga la librería SwiperJS desde `view/frontend/web/js/swiper.min.js`.  
También tenemos `view/frontend/web/css/slider.css`, que define los estilos del slider.

> **Ejemplo de opciones avanzadas en SwiperJS:**  
> ```js
> new Swiper('.<?= $widgetUid ?>-swiper', {
>     loop: true,
>     autoplay: { delay: 3000 },
>     effect: 'fade',
>     pagination: { el: '.swiper-pagination', type: 'bullets' },
> });
> ```

Con esto, el slider se configura automáticamente de acuerdo con los parámetros guardados en el widget.

---

## 🎨 Opciones Avanzadas de Configuración

Desde el widget en el backend, puedes cambiar:
- Cantidad de slides visibles.
- Espaciado entre slides.
- Efectos de transición (fade, cube, flip, etc.).
- Flechas de navegación.
- Paginación con bullets, fracción o progress bar.
- Autoplay con retraso personalizado.

Todas estas configuraciones se reflejan en `slide-widget.phtml`, modificando las opciones de SwiperJS en función de lo que haya definido el usuario.

---

# explicación-técnica-de-archivos

En esta sección, explicaremos **cómo funcionan los archivos clave del módulo**, incluyendo los bloques, plugins y plantillas más importantes.

---

## 📌 `Block/Adminhtml/Widget/`

### 📄 `Dynamic.php`
Este archivo permite la creación de **campos dinámicos** en el administrador.

- Se usa en `widget.xml` como `<parameter name="slides" xsi:type="block">`.
- Permite añadir múltiples slides de forma repetible.
- Inyecta las etiquetas **"Add Slide"** y **"Remove Slide"** en el backend.

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
Este archivo **renderiza cada slide** dentro del administrador.  
Cada slide tiene **imágenes (desktop, mobile) y botones**.

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

Este archivo controla cómo se **renderiza el slider en el frontend**.

### 🔹 Métodos principales:
- `getSlides()`: Recupera los slides desde los datos guardados.
- `getEffect()`, `showArrows()`, etc.: Obtienen la configuración definida en el widget.
- `getWidgetUid()`: Genera un **UID único** para evitar conflictos entre sliders.

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

> 🔹 **Explicación:**  
> - La información de los slides se almacena en un **string separado por comas y barras verticales (`|`)**.
> - `getSlides()` descompone estos valores en un **array estructurado** que luego se usa en `slide-widget.phtml`.

---

## 📌 `Plugin/SetSpecificElement.php`

Este plugin modifica cómo Magento **procesa los parámetros del widget**.

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

> 🔹 **¿Para qué sirve?**  
> - Se encarga de asignar **el tipo correcto a cada parámetro del widget**.
> - Evita errores cuando Magento interpreta los datos dinámicos.

---

# opciones-de-personalización

El widget ofrece múltiples opciones para **personalizar el comportamiento del slider**.  
Todas estas opciones se configuran en el backend y se aplican en `slide-widget.phtml`.

### 🎨 Opciones Disponibles

| Opción | Descripción | Valores |
|--------|------------|---------|
| `slides_per_view` | Número de slides visibles al mismo tiempo | 1, 2, 3, etc. |
| `slides_per_group` | Número de slides que avanzan en cada transición | 1, 2, 3, etc. |
| `effect` | Efecto de transición entre slides | `slide`, `fade`, `cube`, `coverflow`, `flip` |
| `direction` | Dirección del slider | `horizontal`, `vertical` |
| `autoplay` | Activa/desactiva autoplay | `true`, `false` |
| `autoplay_delay` | Tiempo entre cambios de slide (en ms) | `1000`, `3000`, `5000`, etc. |
| `show_arrows` | Mostrar flechas de navegación | `true`, `false` |
| `show_pagination` | Mostrar paginación (bullets) | `true`, `false` |
| `color_primary` | Color principal del slider | Hexadecimal (`#007aff`) |
| `color_secondary` | Color secundario (hover, active) | Hexadecimal (`#ff0000`) |

### ✏️ Ejemplo de Personalización en el Backend

En el widget puedes cambiar **el efecto de transición** y activar **autoplay**:

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

## 🖥️ Aplicación de Personalización en `slide-widget.phtml`

El código de `slide-widget.phtml` lee estos valores y los aplica en el slider:

```js
var swiper = new Swiper('#slider-<?= $widgetUid ?>', {
    loop: true,
    slidesPerView: <?= $this->getSlidesPerView() ?>,
    effect: '<?= $this->getEffect() ?>',
    autoplay: (<?= $this->isAutoplayEnabled() ?>) ? { delay: <?= $this->getAutoplayDelay() ?> } : false,
});
```

> 🔹 **Explicación:**  
> - Se toma la opción **definida por el usuario** en el backend y se usa en SwiperJS.  
> - Si `autoplay` está activado, el slider se mueve automáticamente.

---

## 🎨 Cambios de Estilos en `slider.css`

Si deseas personalizar la apariencia del slider, puedes modificar `slider.css`:

```css
.eniquin-slider-container .swiper-slide {
    background: linear-gradient(to right, #007aff, #ff0000);
    border-radius: 10px;
}
```

> 🔹 **Consejo:** Usa `custom_class` en el widget para añadir clases personalizadas sin modificar el CSS base.

---

# cómo-insertar-el-widget-en-una-página

Magento permite agregar widgets de forma sencilla mediante el **Page Builder** o directamente desde el **administrador de contenido**.  
A continuación, explicamos cómo insertar el widget en diferentes ubicaciones.

---

## 🛠️ Método 1: Insertar el Widget en una Página o Bloque

1. **Ve al panel de administración de Magento.**  
2. Dirígete a **Content > Pages** o **Content > Blocks**, según dónde quieras agregar el slider.  
3. **Edita** la página o bloque donde quieras insertar el slider.  
4. En el editor de contenido, haz clic en **Insert Widget**.  
5. Selecciona **"Eniquin Slider Widget"** en la lista de widgets.  
6. Configura los parámetros del widget según tus preferencias (número de slides, autoplay, efectos, etc.).  
7. Guarda los cambios y visualiza la página en el frontend.  

> 🔹 **Consejo:** Este método es ideal si deseas colocar el slider dentro de un bloque CMS, en la home, o en páginas personalizadas.

---

## 📌 Método 2: Agregar el Widget en un Layout XML

Si deseas insertar el slider en un **layout específico**, puedes hacerlo en un archivo de diseño XML.

Ejemplo: Agregar el widget en la cabecera (`default.xml`):

```xml
<referenceContainer name="header.container">
    <block class="Magento\Widget\Block\Block" name="custom.slider" after="-">
        <arguments>
            <argument name="block_id" xsi:type="string">eniquin_widgetslider_slide_widget</argument>
        </arguments>
    </block>
</referenceContainer>
```

> 🔹 **Consejo:** Usa este método si necesitas que el slider aparezca **en todas las páginas** o en una sección específica del layout.

---

## ✏️ Método 3: Insertar el Widget Manualmente en un `.phtml`

Si necesitas insertar el slider **dentro de una plantilla personalizada**, puedes hacerlo manualmente con el siguiente código:

```php
<?php
echo $this->getLayout()
    ->createBlock('Magento\Widget\Block\Block')
    ->setData(['type' => 'Eniquin_WidgetSlider/Widget/SlideWidget'])
    ->toHtml();
?>
```

> 🔹 **Consejo:** Útil si necesitas mostrar el slider dentro de archivos `.phtml` personalizados en tu tema.

---

# contribuciones

Este módulo es **open-source** y cualquier contribución es bienvenida.  
Si deseas mejorar el código, corregir errores o agregar nuevas características, sigue estos pasos:

## 🚀 Cómo Contribuir

1. **Haz un fork** de este repositorio.  
2. Crea una nueva rama con una descripción clara de tu cambio:  
   ```bash
   git checkout -b feature/nueva-funcionalidad
   ```
3. Realiza las modificaciones necesarias y prueba los cambios en Magento.  
4. **Haz un commit** con una descripción clara:  
   ```bash
   git commit -m "Agregada opción de autoplay al slider"
   ```
5. **Sube los cambios a tu fork** y crea un Pull Request en el repositorio principal.  

## 📌 Reglas para Contribuir

- Si agregas una nueva función, **documenta el código**.  
- Asegúrate de que el módulo siga funcionando correctamente antes de enviar cambios.  
- Si arreglas un bug, **describe el problema y cómo lo solucionaste**.  

---

# licencia

Este proyecto está licenciado bajo la licencia **MIT**, lo que significa que puedes **usar, modificar y distribuir** el código libremente, siempre que incluyas la licencia original.

## 📄 Licencia MIT

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

Puedes utilizar este código para tus propios proyectos, aprender de él o mejorarlo.  
Si realizas cambios y mejoras, ¡sería genial que los compartieras con la comunidad! 🚀

