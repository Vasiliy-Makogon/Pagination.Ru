Получение библиотеки
---
Вы можете <a href="https://github.com/Vasiliy-Makogon/Database/archive/master.zip">скачать её архивом</a>, клонировать с данного сайта или загрузить через composer примерно так:  
<pre>
php composer.phar --prefer-dist require krugozor/pagination "dev-master"
</pre>

Что это и зачем это нужно?
---
Pagination — это простое в установке и настройке решение на языке PHP 5.3, позволяющее быстрое создание т.н. пагинации (от английского слова pagination — порядковая нумерация страниц) — разбивки большого количества информации из таблицы реляционной базы данных на небольшие виртуальные страницы, а также создание визуального интерфейса на языке HTML, позволяющего делать переход по этим страницам.

Пагинация реализуется программистами при разработке гостевых книг, форумов, каталогов объявлений, страниц комментариев, списков статей и в любых других случаях, когда из общего массива данных, хранящихся в СУБД, требуется выбрать ограниченный набор информации, определенный некоторыми рамками.

Если вы — программист, разрабатывающий продукт требующий разбивки информации из базы данных на страницы, то решение Pagination — это то, что вам нужно!

### Небольшое вступление от автора ###
> Данное решение было создано мной на заре моего увлечения веб-программированием много лет назад и до сих пор продолжает работать, я постоянно использую эту наработку в своих программах и ещё ни разу данный код меня не подводил. Поэтому я решил поделиться этим решением с другими программистами. Я надеюсь, код данного проекта избавит вас от рутинной работы написания подобного аналога.

> Хочу отметить, что основную логику кода спустя много лет я так и не смог подвергнуть качественному рефакторингу. Логика кода осталась такой, какой я её создал много лет назад и модифицировать её без причины я не стану — незачем. Хотя признаюсь, я уже давно перестал понимать, как это всё работает. Но это работает!


Краткий обзор возможностей
---
Данный продукт позволяет делать разбивку «на страницы» данных из СУБД, а также создавать интерфейс управления страницами. Интерфейс управления страницами может быть трёх видов:

#### Стандартный тип интерфейса: ####

![](https://github.com/Vasiliy-Makogon/Pagination/blob/master/img/pagination.jpg)

Наиболее распространен в Интернете, интуитивно понятен каждому пользователю. 

#### Инкрементный тип интерфейса: ####

![](https://github.com/Vasiliy-Makogon/Pagination/blob/master/img/increment_pagination.jpg)

Якоря гиперссылок на страницы содержат числовой диапазон, означающий те записи в порядке следования, которые будут выведены при открытии данной гиперссылки. Инкрементным данный тип интерфейса называется потому, что диапазоны увеличиваются по мере удаления от начала данных.

#### Декрементный тип интерфейса: ####

![](https://github.com/Vasiliy-Makogon/Pagination/blob/master/img/decrement_pagination.jpg)

Якоря гиперссылок на страницы содержат числовой диапазон записей, означающий те записи в порядке следования, которые будут выведены при открытии данной гиперссылки. Декрементным данный тип интерфейса называется потому, что диапазоны уменьшаются по мере удаления от начала данных.

#### Описание &laquo;для тупых&raquo; ####

Думаю, опытный пользователь интернета, а уж тем более веб-программист (а вы наверное таковым и являетесь), без труда интуитивно поймет предназначение каждой кнопки-ссылки подобных интерфейсов. Но на всякий случай, я опишу всю эту функциональность на примере *Стандартного типа* (п. 1) интерфейса, когда открыта страница №14 (рис. 1):

* Ссылка **«««** ведёт на самое начало вывода данных, т.е. на самые ранние записи в порядке следования — на страницу №1.
* Ссылка **««** ведёт на предыдущий десяток страниц, будет отображена страница №10, при этом в интерфейсе будет отображён ряд ссылок на страницы с 1 — 10.
* Ссылка **«** ведёт на предыдущую страницу, т.е. на страницу №13.

Аналогично:


* Ссылка **»»»** ведёт на самый конец вывода данных, т.е. на самые поздние записи в порядке следования — на страницу №270.
* Ссылка **»»** ведёт на следующий десяток страниц, будет отображена страница №21, при этом в интерфейсе будет отображён ряд ссылок на страницы с 21 — 30.
* Ссылка **»** ведёт на следующую страницу, т.е. на страницу №15.

Ссылки от **11** до **20** — ссылки на страницы 11 — 20.

#### Через API можно проводить следующие манипуляции с интерфейсом управления страницами: ####


* Изменять тип интерфейса (**Стандартный**, **Инкрементный**, **Декрементный**).
* Всем без исключения видам ссылок интерфейса **назначать свои CSS-классы**.
* **Изменять якоря ссылок «««, ««, «, », »», »»»** устанавливая для них произвольные метки или текста.
* Указывать, **нужно ли показывать ссылки на первую ««« и на последнюю страницу »»»**, а так же **ссылки на предыдущие «« и следующие »»** десятки страниц.
* Указывать для всех гиперссылок **идентификатор фрагмента** (#primer), ссылающийся на некоторую часть открываемого документа.
* **Указывать дополнительные данные** в виде пар *ключ => значение* для подстановки их в QUERY STRING URL-адреса гиперссылок интерфейса.


Подключение и настройка Pagination от «А» до «Я» 
---
> Подразумевается, что читающий данный материал имеет представление о примитивных SQL-запросах, о функциях работы с базой MySql и понимает, что такое объекты.


#### Работа с контроллером ####
Итак, наша задача создать постраничную выборку данных, иногда её ещё называют «отстраничиватель» или просто «пагинатор».
В качестве слоя для работы с СУБД я подключил через composer бибилиотеку **krugozor/database**, но вы можете использовать mysqli, PDO или иной слой, которым вы пользуетесь для получения данных из базы. В примере библиотека Pagination также подключена через composer:
```php
// Предположим, что установили библиотеку Pagination через composer
require  './vendor/autoload.php';
// Алиас для краткости
use Krugozor\Pagination\Manager as Pagination;
// Класс для работы с СУБД
use Krugozor\Database\Mysql\Mysql;

// Соединяемся с нашей базой данной test
$db = Mysql::create('localhost', 'root', '')
      ->setDatabaseName('test')
      ->setCharset('UTF8');
```
Допустим, у нас есть таблица `adverts` содержащая несколько тысяч записей — объявлений, оставленных пользователями. На одной странице мы хотим выводить по 10 заголовков объявлений (поле `header`) из таблицы `adverts`, а под записями мы хотим расположить интерфейс, содержащий 5 ссылок на последующие страницы. Инстанцируем объект класса `Manager`, указав первым аргументом конструктора число 10, вторым — число 5, а третьим — суперглобальный ассоциативный массив `$_REQUEST` (также можно было указать `$_GET`):

```php
$pagination = new Pagination(10, 5, $_REQUEST);
```

Теперь необходимо сделать запрос к вашей СУБД, к таблице `adverts` и получить первые записи. Я надеюсь, вы знаете, как писать SQL запросы на выборку данных и знакомы с выражением LIMIT:

```php
$sql = "SELECT
           SQL_CALC_FOUND_ROWS
           *
        FROM
           `adverts`
        ORDER BY
           `id` DESC
        LIMIT " .
    $pagination->getStartLimit() . "," .
    $pagination->getStopLimit();
```

Как видно из примера, с помощью объекта `$pagination` и его методов `getStartLimit()` и `getStopLimit()` мы получили два значения, которые и подставили в качестве значений для выражения LIMIT. Объект `$pagination` взял на себя работу по вычислению того, какие данные должны возвратить методы `getStartLimit()` и `getStopLimit()`, всё, что требовалось от нас — это написать стандартный SQL запрос на выборку данных и всё!

Параметр `SQL_CALC_FOUND_ROWS` в запросе возвращает количество строк, которые вернул бы оператор SELECT, если бы не был указан LIMIT. Искомое количество строк можно получить при помощи запроса `SELECT FOUND_ROWS()`.

> Обычно программисты ругают SQL_CALC_FOUND_ROWS и предлагают вместо него использовать стандартный COUNT(*) для подсчёта строк. Я не буду вдаваться в этот холивор, скажу лишь то, что на небольших проектах SQL_CALC_FOUND_ROWS полностью себя оправдывает, а для данного примера он подходит идеально. Ну и основной плюс SQL_CALC_FOUND_ROWS в том, что не нужно дублировать запрос с функцией COUNT().

Выполним этот запрос и получим данные, пока только в виде ресурса:

```php
$result = $db->query($sql);
```

А теперь получим количество всех строк, полученных с помощью вышеупомянутой функции `FOUND_ROWS()`, и «скормим» это значение  объекту `$pagination` посредством метода `setCount()`:

```php
$pagination->setCount(
    $db->query('SELECT FOUND_ROWS()')->getOne()
);
```

Всё, на этом наша работа напрямую с объектом `$pagination` заканчивается. Давайте полученные данные из таблицы `adverts` сохраним в переменной $data:

```php
while (($row = $result->fetch_assoc()) !== null) {
    $data[] = $row;
}
```

И подключим HTML-шабон, который мы создадим чуть ниже:

```php
include("template.phtml");
```

**В итоге код нашего скрипта должен выглядеть следующим образом:**

```php
<?php
// Предположим, что установили библиотеку через composer
require  './vendor/autoload.php';
// Алиас для краткости
use Krugozor\Pagination\Manager as Pagination;
// Класс для работы с СУБД
use Krugozor\Database\Mysql\Mysql;

// Соединяемся с нашей базой данной test
$db = Mysql::create('localhost', 'root', '')
      ->setDatabaseName('test')
      ->setCharset('UTF8');

$pagination = new Pagination(10, 5, $_REQUEST);

$sql = "SELECT
           SQL_CALC_FOUND_ROWS
           *
        FROM
           `adverts`
        ORDER BY
           `id` DESC
        LIMIT " .
    $pagination->getStartLimit() . "," .
    $pagination->getStopLimit();
    
// Выполнили запрос с SQL_CALC_FOUND_ROWS
$result = $db->query($sql);

// Указали объекту пагинации сколько всего данных, попадающих под условие SQL-запроса
$pagination->setCount(
    $db->query('SELECT FOUND_ROWS()')->getOne()
);

// Полученные данные записали в $data
while (($row = $result->fetch_assoc()) !== null) {
    $data[] = $row;
}

include("template.phtml");
```

Как видите, всё *очень* просто — вся работа пагинации заключена в нескольких строчках! 

#### Работа с шаблоном: вывод записей ####

Создайте файл шаблона `template.html` примерно такого содержания:

```php
<!DOCTYPE html>
<html>
<head>
    <title>Бесплатные объявления</title>
</head>
<body>

    <?php
    $counter = $pagination->getAutoincrementNum();
    ?>
    <?php foreach($data as $advert): ?>
        <p><?=($counter++)?>. <?=htmlspecialchars($advert['header']);?></p>
    <?php endforeach; ?>

</body>
</html>
```

Данный шаблон с помощью цикла `foreach` выводит записи, которые мы поместили в массив `$data` в PHP-скрипте, функция `htmlspecialchars` я надеюсь, вы знаете, что делает. Что делает нижестоящая строка кода:

```php
$counter = $paginationManager->getAutoincrementNum();
```

Метод `getAutoincrementNum()` объекта возвращает число для обозначения **визуального** идентификатора записи в порядке вывода, которое в цикле нужно инкрементировать, т.е. увеличивать, что мы и сделали. Взглянем на пример такого результата:

![](https://github.com/Vasiliy-Makogon/Pagination/blob/master/img/p1.png)

— как видно, записи нумеруются с цифры 1 и счетчик увеличивается по мере вывода записей.

Аналогично, существует метод `getAutodecrementNum()`, полученное значение которого в цикле надо декрементировать, т.е. уменьшать. Давайте попробуем это сделать:

```php
<!DOCTYPE html>
<html>
<head>
    <title>Бесплатные объявления</title>
</head>
<body>

    <?php
    $counter = $pagination->getAutodecrementNum();
    ?>
    <?php foreach($data as $advert): ?>
        <p><?=($counter--)?>. <?=htmlspecialchars($advert['header']);?></p>
    <?php endforeach; ?>

</body>
</html>
```

Взглянем на пример такого результата:

![](https://github.com/Vasiliy-Makogon/Pagination/blob/master/img/p2.png)

— как видно, записи нумеруются с максимального количества записей в базе и счетчик уменьшается по мере вывода записей. Определять, какой вид числовых идентификаторов использовать — решать вам. Обычно, если записи выводятся начиная с новых (`ORDER BY `id` DESC`), то предпочтительно использовать метод `getAutodecrementNum()`, если начиная со старых (`ORDER BY `id` ASC`) — `getAutoincrementNum()`. А можно вообще не нумеровать записи — в 80% случаев я так и поступаю, т.к. в визуальная нумерация записей нужна далеко не всегда.

#### Работа с шаблоном: создание интерфейса для перехода между страницами ####

Интерфейс пагинатора создается с помощью объекта `Helper` непосредственно в шаблоне. Класс `Helper` имеет множество методов для гибкой настройки интерфейса пагинации:

```php
<!DOCTYPE html>
<html>
<head>
    <title>Бесплатные объявления</title>
    <style type="text/css">
        .normal_link,
        .active_link{
            font-size:15px;
            border:1px solid #ccc;
            padding:3px;
        }
        .active_link{
            border:1px solid red;
        }
    </style>
</head>
<body>

    <?php
    $counter = $pagination->getAutoincrementNum()
    ?>
    <?php foreach($data as $advert): ?>
        <p><?=($counter++)?>. <?=htmlspecialchars($advert['advert_header']);?></p>
    <?php endforeach; ?>

    <?php
    $pagination->getHelper()
        // Настройка внешнего вида пагинатора
        // Хотим получить стандартный вид пагинации
        ->setPaginationType(\Krugozor\Pagination\Helper::PAGINATION_NORMAL_TYPE)
        // Устанавливаем CSS-класс каждого элемента <a> в интерфейсе пагинатора
        ->setCssNormalLinkClass("normal_link")
        // Устанавливаем CSS-класс элемента <span> в интерфейсе пагинатора,
        // страница которого открыта в текущий момент.
        ->setCssActiveLinkClass("active_link")
        // Параметр для query string гиперссылки
        ->setRequestUriParameter("param_1", "value_1")
        // Параметр для query string гиперссылки
        ->setRequestUriParameter("param_2", "value_2")
        // Устанавливаем идентификатор фрагмента гиперссылок пагинатора
        ->setFragmentIdentifier("foo");
    ?>
    <div>
        <hr>
        Всего записей: <strong><?=$pagination->getCount()?></strong>
        <?php if ($pagination->getCount()): ?>
            <br /><br /><span>Страницы:</span>
            <?=$pagination->getHelper()->getHtml()?>
        <?php endif; ?>
    </div>

</body>
</html>
```

Результат: 

![](https://github.com/Vasiliy-Makogon/Pagination/blob/master/img/p3.png)


Обратите внимние:
* В строке адреса, в QUERY STRING, присутствуют переданные параметры `param_1=value_1` и `param_2=value_2` - так мы можем передавать необходимые параметры при переходе между страницами. Так же, в строке адреса, пристутствует указанный идентификатор фрагмента `#foo`.
* Были изменены стили ссылок интерфейса пагинатора посредством указания необъодимых классов.

**Можно создавать различные вариации пагинации:**

```php
    // ... 
    <?php
    $pagination->getHelper()
        // Настройка внешнего вида пагинатора
        // Хотим получить стандартный вид пагинации
        ->setPaginationType(\Krugozor\Pagination\Helper::PAGINATION_INCREMENT_TYPE)
        // Устанавливаем CSS-класс каждого элемента <a> в интерфейсе пагинатора
        ->setCssNormalLinkClass("normal_link")
        // Устанавливаем CSS-класс элемента <span> в интерфейсе пагинатора,
        // страница которого открыта в текущий момент.
        ->setCssActiveLinkClass("active_link")
        // Параметр для query string гиперссылки
        ->setRequestUriParameter("param_1", "value_1")
        // Параметр для query string гиперссылки
        ->setRequestUriParameter("param_2", "value_2")
        // Устанавливаем идентификатор фрагмента гиперссылок пагинатора
        ->setFragmentIdentifier("result4")
        // Не показываем сслыки перехода на первую («««)...
        ->setViewFirstPageLabel(false)
        // ..и последнюю (»»») страницу
        ->setViewLastPageLabel(false)
        // Не показываем ссылки перехода между блоками страниц («« и »»)
        ->setViewPreviousBlockLabel(false)
        ->setViewNextBlockLabel(false)
        // Заменим якоря тегов перехода между страницами (« и »)
        ->setPreviousPageAnchor("&larr; Туда")
        ->setNextPageAnchor("Сюда &rarr;");
    ?>
   // ...
```

Результат: 

![](https://github.com/Vasiliy-Makogon/Pagination/blob/master/img/p4.png)


