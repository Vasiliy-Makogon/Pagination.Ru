<?php 
/** 
 * @link http://pagination.ru/
 * @author Vasiliy Makogon, makogon.vs@gmail.com, makogon-vs@yandex.ru
 */
class Pagination_Helper
{
    /**
     * Стандартный вид пагинации:
     * «««  ««  «  1 2 3 4 5 6 7 8 9 10  »  »»  »»»
     *
     * @var int
     */
    const PAGINATION_NORMAL_TYPE = 1;

    /**
     * Вид интервальной декрементной пагинации:
     * «««  ««  «  50-41 40-31 30-21 20-11 10-1  »  »»  »»»
     *
     * @var int
     */
    const PAGINATION_DECREMENT_TYPE = 2;

    /**
     * Вид интервальной инкрементной пагинации:
     * «««  ««  «  1-10 11-20 21-30 31-40 41-50  »  »»  »»»
     *
     * @var int
     */
    const PAGINATION_INCREMENT_TYPE = 3;

    /**
     * @var Pagination_Manager
     */
    private $manager;

    /**
     * Хранилище CSS-классов для каждого <a> элемента пагинатора.
     *
     * @var array
     */
    private $styles = array();

    /**
     * Хранилище пар ключ=>значение для подстановки в QUERY_STRING
     * гиперссылок пагинатора.
     *
     * @var array
     */
    private $request_uri_params = array();

    /**
     * Якоря и title для всех элементов <a> пагинатора.
     *
     * @var array
     */
    private $html = array
    (
        'first_page_anchor'  => '«««',
        'previous_block_anchor'  => '««',
        'previous_page_anchor'   => '«',
        'next_page_anchor'  => '»',
        'next_block_anchor' => '»»',
        'last_page_anchor'   => '»»»',

        'first_page_title' => 'На первую страницу',
        'previous_block_title' => 'Предыдущие страницы',
        'previous_page_title'  => 'Предыдущая страница',
        'next_page_title' => 'Следующая страница',
        'next_block_title' => 'Следующие страницы',
        'last_page_title'  => 'На последнюю страницу',
    );

    /**
     * Показывать ли элемент <a> '«««'.
     *
     * @var bool
     */
    private $view_first_page_label = true;

    /**
     * Показывать ли элемент <a> '»»»'.
     *
     * @var bool
     */
    private $view_last_page_label = true;

    /**
     * Показывать ли элемент <a> '««'.
     *
     * @var bool
     */
    private $view_previous_block_label = true;

    /**
     * Показывать ли элемент <a> '»»'.
     *
     * @var bool
     */
    private $view_next_block_label = true;

    /**
     * Идентификатор фрагмента (#primer), ссылающийся на некоторую часть открываемого документа.
     *
     * @var string
     */
    private $fragment_identifier;

    /**
     * Тип интерфейса пагинатора (см. константы класса PAGINATION_*_TYPE).
     *
     * @var int
     */
    private $pagination_type;

    /**
     * @param Pagination_Manager $manager
     */
    public function __construct(Pagination_Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Возвращает объект Pagination_Manager
     *
     * @param void
     * @return Pagination_Manager
     */
    public function getPagination()
    {
        return $this->manager;
    }

    /**
     * Устанавливает тип интерфейса пагинатора.
     *
     * @param int
     * @return Pagination_Helper
     */
    public function setPaginationType($pagination_type)
    {
        $this->pagination_type = (int) $pagination_type;

        return $this;
    }

    /**
     * Устанавливает очередной параметр для QUERY_STRING
     * гиперссылок пагинатора.
     *
     * @param string $key
     * @param string $value
     * @return Pagination_Helper
     */
    public function setRequestUriParameter($key, $value)
    {
        $this->request_uri_params[$key] = (string) $value;

        return $this;
    }

    /**
     * Устанавливает, показывать ли элемент <a> '«««'.
     *
     * @param bool $value
     * @return Pagination_Helper
     */
    public function setViewFirstPageLabel($value)
    {
        $this->view_first_page_label = (bool) $value;

        return $this;
    }

    /**
     * Устанавливает, показывать ли элемент <a> '»»»'.
     *
     * @param bool $value
     * @return Pagination_Helper
     */
    public function setViewLastPageLabel($value)
    {
        $this->view_last_page_label = (bool) $value;

        return $this;
    }

    /**
     * Устанавливает, показывать ли элемент <a> '««'.
     *
     * @param bool $value
     * @return Pagination_Helper
     */
    public function setViewPreviousBlockLabel($value)
    {
        $this->view_previous_block_label = (bool) $value;

        return $this;
    }

    /**
     * Устанавливает, показывать ли элемент <a> '»»'.
     *
     * @param bool $value
     * @return Pagination_Helper
     */
    public function setViewNextBlockLabel($value)
    {
        $this->view_next_block_label = (bool) $value;

        return $this;
    }

    /**
     * Устанавливает идентификатор фрагмента (#primer) гиперссылок пагинатора.
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setFragmentIdentifier($fragment_identifier)
    {
        $this->fragment_identifier = trim((string) $fragment_identifier, ' #');

        return $this;
    }

    /**
     * Устанавливает CSS-класс каждого элемента <a> в интерфейсе пагинатора.
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setCssNormalLinkClass($class)
    {
        $this->styles['normal_link_class'] = (string) $class;

        return $this;
    }

    /**
     * Устанавливает CSS-класс элемента <span> в интерфейсе пагинатора,
     * страница которого открыта в текущий момент.
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setCssActiveLinkClass($class)
    {
        $this->styles['active_link_class'] = (string) $class;

        return $this;
    }

    /**
     * Устанавливает CSS-класс элемента <a> '«««'
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setCssFirstPageClass($class)
    {
        $this->styles['first_page_class'] = (string) $class;

        return $this;
    }

    /**
     * Устанавливает CSS-класс элемента <a> '»»»'
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setCssLastPageClass($class)
    {
        $this->styles['last_page_class'] = (string) $class;

        return $this;
    }

    /**
     * Устанавливает CSS-класс элемента <a> '««'
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setCssPreviousBlockClass($class)
    {
        $this->styles['previous_block_class'] = (string) $class;

        return $this;
    }

    /**
     * Устанавливает CSS-класс элемента <a> '»»'
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setCssNextBlockClass($class)
    {
        $this->styles['next_block_class'] = (string) $class;

        return $this;
    }

    /**
     * Устанавливает CSS-класс элемента <a> '«'
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setCssPreviousPageClass($class)
    {
        $this->styles['previous_page_class'] = (string) $class;

        return $this;
    }

    /**
     * Устанавливает CSS-класс элемента <a> '»'
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setCssNextPageClass($class)
    {
        $this->styles['next_page_class'] = (string) $class;

        return $this;
    }

    /**
     * Устанавливает якорь для элемента <a> '«««'
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setFirstPageAnchor($anchor)
    {
        $this->html['first_page_anchor'] = (string) $anchor;

        return $this;
    }

    /**
     * Устанавливает якорь для элемента <a> '»»»'
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setLastPageAnchor($anchor)
    {
        $this->html['last_page_anchor'] = (string) $anchor;

        return $this;
    }

    /**
     * Устанавливает якорь для элемента <a> '««'
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setPreviousBlockAnchor($anchor)
    {
        $this->html['previous_block_anchor'] = (string) $anchor;

        return $this;
    }

    /**
     * Устанавливает якорь для элемента <a> '»»'
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setNextBlockAnchor($anchor)
    {
        $this->html['next_block_anchor'] = (string) $anchor;

        return $this;
    }

    /**
     * Устанавливает якорь для элемента <a> '«'
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setPreviousPageAnchor($anchor)
    {
        $this->html['previous_page_anchor'] = (string) $anchor;

        return $this;
    }

    /**
     * Устанавливает якорь для элемента <a> '»'
     *
     * @param string
     * @return Pagination_Helper
     */
    public function setNextPageAnchor($anchor)
    {
        $this->html['next_page_anchor'] = (string) $anchor;

        return $this;
    }

    /**
     * Формирует и возвращает HTML-код строки навигации.
     *
     * @param void
     * @return string
     */
    public function getHtml()
    {
        ob_start();

        $self_uri = $this->createRequestUri();
        $qs = $this->createQueryString();
    ?>
    <? if ($this->view_first_page_label && $this->manager->getCurrentSeparator() && $this->manager->getCurrentSeparator() != 1): ?>
        &nbsp;<a<?=$this->createInlineCssClassDeclaration('first_page_class', 'normal_link_class')?> title="<?=$this->html['first_page_title']?>" href="<?=$self_uri?>?<?=$qs?><?=$this->manager->getPageName()?>=1&amp;<?=$this->manager->getSeparatorName()?>=1<?=$this->createFragmentIdentifier()?>"><?=$this->html['first_page_anchor']?></a>&nbsp;
    <? endif; ?>

    <? if ($this->view_previous_block_label && $this->manager->getPreviousBlockSeparator()): ?>
        <a<?=$this->createInlineCssClassDeclaration('previous_block_class', 'normal_link_class')?> title="<?=$this->html['previous_block_title']?>" href="<?=$self_uri?>?<?=$qs?><?=$this->manager->getPageName()?>=<?=$this->manager->getPageForPreviousBlock()?>&amp;<?=$this->manager->getSeparatorName()?>=<?=$this->manager->getPreviousBlockSeparator()?><?=$this->createFragmentIdentifier()?>"><?=$this->html['previous_block_anchor']?></a>&nbsp;
    <? endif; ?>

    <? if($this->manager->getPreviousPageSeparator() && $this->manager->getPreviousPage()): ?>
        <a<?=$this->createInlineCssClassDeclaration('previous_page_class', 'normal_link_class')?> title="<?=$this->html['previous_page_title']?>" href="<?=$self_uri?>?<?=$qs?><?=$this->manager->getPageName()?>=<?=$this->manager->getPreviousPage()?>&amp;<?=$this->manager->getSeparatorName()?>=<?=$this->manager->getPreviousPageSeparator()?><?=$this->createFragmentIdentifier()?>"><?=$this->html['previous_page_anchor']?></a>&nbsp;
    <? endif; ?>

    <? foreach($this->manager->getTemplateData() as $row): ?>
        <? if($this->manager->getCurrentPage() == $row["page"]): ?>
            <span<?=$this->createInlineCssClassDeclaration('active_link_class')?>><?=$this->createHyperlinkAnchor($row)?></span>
        <? else: ?>
            <a<?=$this->createInlineCssClassDeclaration('normal_link_class')?> href="<?=$self_uri?>?<?=$qs?><?=$this->manager->getSeparatorName()?>=<?=$row["separator"]?>&amp;<?=$this->manager->getPageName()?>=<?=$row["page"]?><?=$this->createFragmentIdentifier()?>"><?=$this->createHyperlinkAnchor($row)?></a>
        <? endif; ?>
    <? endforeach; ?>

    <? if($this->manager->getNextPageSeparator() && $this->manager->getNextPage()): ?>
        &nbsp;<a<?=$this->createInlineCssClassDeclaration('next_page_class', 'normal_link_class')?> title="<?=$this->html['next_page_title']?>" href="<?=$self_uri?>?<?=$qs?><?=$this->manager->getPageName()?>=<?=$this->manager->getNextPage()?>&amp;<?=$this->manager->getSeparatorName()?>=<?=$this->manager->getNextPageSeparator()?><?=$this->createFragmentIdentifier()?>"><?=$this->html['next_page_anchor']?></a>
    <? endif; ?>

    <? if($this->view_next_block_label && $this->manager->getNextBlockSeparator()): ?>
        &nbsp;<a<?=$this->createInlineCssClassDeclaration('next_block_class', 'normal_link_class')?> title="<?=$this->html['next_block_title']?>" href="<?=$self_uri?>?<?=$qs?><?=$this->manager->getSeparatorName()?>=<?=$this->manager->getNextBlockSeparator()?><?=$this->createFragmentIdentifier()?>"><?=$this->html['next_block_anchor']?></a>
    <? endif; ?>

    <? if ($this->view_last_page_label && $this->manager->getLastSeparator() && $this->manager->getCurrentSeparator() != $this->manager->getLastSeparator()): ?>
        &nbsp;<a<?=$this->createInlineCssClassDeclaration('last_page_class', 'normal_link_class')?> title="<?=$this->html['last_page_title']?>" href="<?=$self_uri?>?<?=$qs?><?=$this->manager->getPageName()?>=<?=$this->manager->getLastPage()?>&amp;<?=$this->manager->getSeparatorName()?>=<?=$this->manager->getLastSeparator()?><?=$this->createFragmentIdentifier()?>"><?=$this->html['last_page_anchor']?></a>
    <? endif; ?>
<?php
        $str = ob_get_contents();
        ob_end_clean();

        return $str;
    }

    /**
     * Создаёт якорь для элемента <a> в зависимости от типа $this->pagination_type.
     *
     * @param array $params
     * @return string
     */
    private function createHyperlinkAnchor(array $params)
    {
        switch ($this->pagination_type)
        {
            case self::PAGINATION_DECREMENT_TYPE:
                return $params['decrement_anhor'];

            case self::PAGINATION_INCREMENT_TYPE:
                return $params['increment_anhor'];

            case self::PAGINATION_NORMAL_TYPE:
            default:
                return $params['page'];
        }
    }

    /**
     * Возвращает строку вида `class="class_name"` если $class_name объявлен и
     * описан в $this->styles[$class_name].
     * В обратном случае возвращает строку вида `class="replacement_class_name"`,
     * если $replacement_class_name объявлен в качестве аргумента метода и
     * описан в $this->styles[$replacement_class_name].
     * Если $replacement_class_name не объявлен, возвращается пустая строка.
     *
     * @param string имя CSS-класса
     * @param string имя CSS-класса
     * @return string
     */
    private function createInlineCssClassDeclaration($class_name, $replacement_class_name=null)
    {
        return !empty($this->styles[$class_name])
               ? ' class="' . $this->styles[$class_name] . '"'
               : ($replacement_class_name === null
                  ? ''
                  : call_user_func_array(array($this, __METHOD__), array($replacement_class_name))
                 );
    }

    /**
     * Возвращает идентификатор фрагмента с символом #
     * для подстановки непосредственно в URL-адрес.
     *
     * @param void
     * @return string
     */
    private function createFragmentIdentifier()
    {
        return !empty($this->fragment_identifier) ? '#' . $this->fragment_identifier : '';
    }

    /**
     * Возвращает REQUEST_URI без QUERY_STRING.
     *
     * @param void
     * @return string
     */
    private function createRequestUri()
    {
        if (strpos($_SERVER["REQUEST_URI"], '?') !== false)
        {
            return substr($_SERVER["REQUEST_URI"], 0, strpos($_SERVER["REQUEST_URI"], '?'));
        }
        else
        {
            return $_SERVER["REQUEST_URI"];
        }
    }

    /**
     * Создает строку QUERY_STRING из массива параметров $this->request_uri_params.
     *
     * @param void
     * @return string
     */
    private function createQueryString()
    {
        $query_string = '';

        foreach ($this->request_uri_params as $key => $value)
        {
            if ((string) $value !== '')
            {
                $query_string .= $key . '=' . htmlentities(urlencode($value)) . '&amp;';
            }
        }

        return $query_string;
    }
}