<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace sq\base;

use Yii;
use Closure;
use yii\grid\DataColumn;
use yii\i18n\Formatter;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\BaseListView;
use yii\base\Model;
use yii\grid\GridViewAsset;

/**
 * The GridView widget is used to display data in a grid.
 *
 * It provides features like sorting, paging and also filtering the data.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ExcelView extends BaseListView
{
    const FILTER_POS_HEADER = 'header';
    const FILTER_POS_FOOTER = 'footer';
    const FILTER_POS_BODY = 'body';
    // phpExcel对象
    private $objPHPExcel = null;
    // 自增行号
    private $rowNo = 0;
    // 允许最大行数
    private $maxRowCount = 5000;

    //fixme zhyf
    public $fileName = 'data';

    /**
     * @var string the default data column class if the class name is not explicitly specified when configuring a data column.
     * Defaults to 'yii\grid\DataColumn'.
     */
    public $dataColumnClass;
    /**
     * @var string the caption of the grid table
     * @see captionOptions
     */
    public $caption;
    /**
     * @var array the HTML attributes for the caption element.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     * @see caption
     */
    public $captionOptions = [];
    /**
     * @var array the HTML attributes for the grid table element.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $tableOptions = ['class' => 'table table-striped table-bordered'];
    /**
     * @var array the HTML attributes for the container tag of the grid view.
     * The "tag" element specifies the tag name of the container element and defaults to "div".
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 'grid-view'];
    /**
     * @var array the HTML attributes for the table header row.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $headerRowOptions = [];
    /**
     * @var array the HTML attributes for the table footer row.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $footerRowOptions = [];
    /**
     * @var array|Closure the HTML attributes for the table body rows. This can be either an array
     * specifying the common HTML attributes for all body rows, or an anonymous function that
     * returns an array of the HTML attributes. The anonymous function will be called once for every
     * data model returned by [[dataProvider]]. It should have the following signature:
     *
     * ```php
     * function ($model, $key, $index, $grid)
     * ```
     *
     * - `$model`: the current data model being rendered
     * - `$key`: the key value associated with the current data model
     * - `$index`: the zero-based index of the data model in the model array returned by [[dataProvider]]
     * - `$grid`: the GridView object
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $rowOptions = [];
    /**
     * @var Closure an anonymous function that is called once BEFORE rendering each data model.
     * It should have the similar signature as [[rowOptions]]. The return result of the function
     * will be rendered directly.
     */
    public $beforeRow;
    /**
     * @var Closure an anonymous function that is called once AFTER rendering each data model.
     * It should have the similar signature as [[rowOptions]]. The return result of the function
     * will be rendered directly.
     */
    public $afterRow;
    /**
     * @var boolean whether to show the header section of the grid table.
     */
    public $showHeader = true;
    /**
     * @var boolean whether to show the footer section of the grid table.
     */
    public $showFooter =  false;
    /**
     * @var boolean whether to show the grid view if [[dataProvider]] returns no data.
     */
    public $showOnEmpty = true;
    /**
     * @var array|Formatter the formatter used to format model attribute values into displayable texts.
     * This can be either an instance of [[Formatter]] or an configuration array for creating the [[Formatter]]
     * instance. If this property is not set, the "formatter" application component will be used.
     */
    public $formatter;
    /**
     * @var array grid column configuration. Each array element represents the configuration
     * for one particular grid column. For example,
     *
     * ```php
     * [
     *     ['class' => SerialColumn::className()],
     *     [
     *         'class' => DataColumn::className(),
     *         'attribute' => 'name',
     *         'format' => 'text',
     *         'label' => 'Name',
     *     ],
     *     ['class' => CheckboxColumn::className()],
     * ]
     * ```
     *
     * If a column is of class [[DataColumn]], the "class" element can be omitted.
     *
     * As a shortcut format, a string may be used to specify the configuration of a data column
     * which only contains "attribute", "format", and/or "label" options: `"attribute:format:label"`.
     * For example, the above "name" column can also be specified as: `"name:text:Name"`.
     * Both "format" and "label" are optional. They will take default values if absent.
     */
    public $columns = [];
    /**
     * @var string the HTML display when the content of a cell is empty
     */
    public $emptyCell = '&nbsp;';
    /**
     * @var \yii\base\Model the model that keeps the user-entered filter data. When this property is set,
     * the grid view will enable column-based filtering. Each data column by default will display a text field
     * at the top that users can fill in to filter the data.
     *
     * Note that in order to show an input field for filtering, a column must have its [[DataColumn::attribute]]
     * property set or have [[DataColumn::filter]] set as the HTML code for the input field.
     *
     * When this property is not set (null) the filtering feature is disabled.
     */
    public $filterModel;
    /**
     * @var string|array the URL for returning the filtering result. [[Url::to()]] will be called to
     * normalize the URL. If not set, the current controller action will be used.
     * When the user makes change to any filter input, the current filtering inputs will be appended
     * as GET parameters to this URL.
     */
    public $filterUrl;
    /**
     * @var string additional jQuery selector for selecting filter input fields
     */
    public $filterSelector;
    /**
     * @var string whether the filters should be displayed in the grid view. Valid values include:
     *
     * - [[FILTER_POS_HEADER]]: the filters will be displayed on top of each column's header cell.
     * - [[FILTER_POS_BODY]]: the filters will be displayed right below each column's header cell.
     * - [[FILTER_POS_FOOTER]]: the filters will be displayed below each column's footer cell.
     */
    public $filterPosition = self::FILTER_POS_BODY;
    /**
     * @var array the HTML attributes for the filter row element.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $filterRowOptions = ['class' => 'filters'];
    /**
     * @var array the options for rendering the filter error summary.
     * Please refer to [[Html::errorSummary()]] for more details about how to specify the options.
     * @see renderErrors()
     */
    public $filterErrorSummaryOptions = ['class' => 'error-summary'];
    /**
     * @var array the options for rendering every filter error message.
     * This is mainly used by [[Html::error()]] when rendering an error message next to every filter input field.
     */
    public $filterErrorOptions = ['class' => 'help-block'];
    /**
     * @var string the layout that determines how different sections of the list view should be organized.
     * The following tokens will be replaced with the corresponding section contents:
     *
     * - `{summary}`: the summary section. See [[renderSummary()]].
     * - `{errors}`: the filter model error summary. See [[renderErrors()]].
     * - `{items}`: the list items. See [[renderItems()]].
     * - `{sorter}`: the sorter. See [[renderSorter()]].
     * - `{pager}`: the pager. See [[renderPager()]].
     */
    public $layout = "{items}";


    /**
     * Initializes the grid view.
     * This method will initialize required property values and instantiate [[columns]] objects.
     */
    public function init()
    {
        parent::init();
        if ($this->formatter == null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }
        if (!isset($this->filterRowOptions['id'])) {
            $this->filterRowOptions['id'] = $this->options['id'] . '-filters';
        }

        $this->initColumns();
    }

    /**
     * fixme zhyf
     * Runs the widget.
     */
    public function run()
    {
        require_once dirname(__FILE__) . '/PHPExcel.php';

        // Create new PHPExcel object
        $this->objPHPExcel = new \PHPExcel();

        // Set document properties
        $this->objPHPExcel->getProperties()
            /*
            ->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file")
            */
        ;

        $this->renderItems();
// Rename worksheet
        $fileName = $this->fileName;
        $this->objPHPExcel->getActiveSheet()->setTitle($fileName);

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $this->objPHPExcel->setActiveSheetIndex(0);

        ob_end_clean();
// Redirect output to a client’s web browser (Excel5)
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header('Content-Disposition: inline;filename="' . $fileName . '.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;


        /*
        $fileName = $this->fileName;
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: inline; filename=\"" . $fileName . ".xls\"");
        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n
            <Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"
            xmlns:x=\"urn:schemas-microsoft-com:office:excel\"
            xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"
            xmlns:html=\"http://www.w3.org/TR/REC-html40\">";
        echo "\n<Worksheet ss:Name=\"" . $fileName . "\">\n<Table>\n";
        $this->renderItems();
        echo "</Table>\n</Worksheet>\n</Workbook>";
        */
    }

    /**
     * Renders validator errors of filter model.
     * @return string the rendering result.
     */
    public function renderErrors()
    {
        if ($this->filterModel instanceof Model && $this->filterModel->hasErrors()) {
            return Html::errorSummary($this->filterModel, $this->filterErrorSummaryOptions);
        } else {
            return '';
        }
    }

    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        switch ($name) {
            case "{errors}":
                return $this->renderErrors();
            default:
                return parent::renderSection($name);
        }
    }

    /**
     * Returns the options for the grid view JS widget.
     * @return array the options
     */
    protected function getClientOptions()
    {
        $filterUrl = isset($this->filterUrl) ? $this->filterUrl : Yii::$app->request->url;
        $id = $this->filterRowOptions['id'];
        $filterSelector = "#$id input, #$id select";
        if (isset($this->filterSelector)) {
            $filterSelector .= ', ' . $this->filterSelector;
        }

        return [
            'filterUrl' => Url::to($filterUrl),
            'filterSelector' => $filterSelector,
        ];
    }

    /**
     * fixme zhyf
     * Renders the data models for the grid view.
     */
    public function renderItems()
    {
        $this->renderTableHeader();
        $this->renderTableBody();
    }

    /**
     * Renders the caption element.
     * @return bool|string the rendered caption element or `false` if no caption element should be rendered.
     */
    public function renderCaption()
    {
        if (!empty($this->caption)) {
            return Html::tag('caption', $this->caption, $this->captionOptions);
        } else {
            return false;
        }
    }

    /**
     * Renders the column group HTML.
     * @return bool|string the column group HTML or `false` if no column group should be rendered.
     */
    public function renderColumnGroup()
    {
        $requireColumnGroup = false;
        foreach ($this->columns as $column) {
            /* @var $column Column */
            if (!empty($column->options)) {
                $requireColumnGroup = true;
                break;
            }
        }
        if ($requireColumnGroup) {
            $cols = [];
            foreach ($this->columns as $column) {
                $cols[] = Html::tag('col', '', $column->options);
            }

            return Html::tag('colgroup', implode("\n", $cols));
        } else {
            return false;
        }
    }

    /**
     * Get Excel Column String
     * @params $columnNo
     * @return String excel column String
     */
    private function getExcelColumnByNo($intNo) {
        $wordTable = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $wordLength = 26;
        $firstNo = floor($intNo / $wordLength);
        $secondNo = $intNo % $wordLength;
        return ($firstNo > 0 ? $wordTable{$firstNo - 1} : '').($wordTable{$secondNo});
    }

    /**
     * Renders the table header.
     * @return string the rendering result.
     */
    public function renderTableHeader()
    {
        $columnNo = 0;
        $this->rowNo++;
        foreach ($this->columns as $column) {
            $this->objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicitByColumnAndRow(($columnNo++), ($this->rowNo), $column->renderHeaderCell());
        }
        /*
        $cells = [];
        foreach ($this->columns as $column) {
            $cells[] = "<Cell><Data ss:Type=\"String\">" . $column->renderHeaderCell() . "</Data></Cell>\n";
        }
        echo "<Row>\n" . implode('', $cells) . "</Row>\n";
        */
    }

    /**
     * fixme zhyf
     * Renders the table body.
     * @return string the rendering result.
     */
    public function renderTableBody()
    {
        $limit = 100;
        $offset = 0;

        do {
            $rows = [];
            $query = clone $this->dataProvider->query;
            $models = $query->limit($limit)->offset($offset)->all();
            foreach ($models as $index => $model) {
                $primaryKey = $model->primaryKey();
                $key = $primaryKey[0];
                /*
                if ($this->beforeRow !== null) {
                    $row = call_user_func($this->beforeRow, $model, $key, $index, $this);
                    if (!empty($row)) {
                        $rows[] = $row;
                    }
                }
                */

                $rows[] = $this->renderTableRow($model, $key, $index);

                /*
                if ($this->afterRow !== null) {
                    $row = call_user_func($this->afterRow, $model, $key, $index, $this);
                    if (!empty($row)) {
                        $rows[] = $row;
                    }
                }
                */
            }

            $offset += $limit;
            // echo join("\n", $rows);
            unset($rows);
            // ob_flush();
        } while($models);
    }

    /**
     * Renders a table row with the given data model and key.
     * @param mixed $model the data model to be rendered
     * @param mixed $key the key associated with the data model
     * @param integer $index the zero-based index of the data model among the model array returned by [[dataProvider]].
     * @return string the rendering result
     */
    public function renderTableRow($model, $key, $index)
    {
        $cells = [];
        /* @var $column ExcelColumn */
        $columnNo = 0;
        $this->rowNo++;
        foreach ($this->columns as $column) {
            if($column instanceof DataColumn) {
            //     $cells[] = "<Cell><Data ss:Type=\"String\">" . $column->renderDataCell($model, $key, $index) . "</Data></Cell>\n";
//                echo iconv('UTF-8', 'GB2312', $column->renderDataCell($model, $key, $index))."<br/>";
                // $this->rowNo<=$this->maxRowCount &&
                $this->objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicitByColumnAndRow(($columnNo++), ($this->rowNo), $column->renderDataCell($model, $key, $index));
            }
        }
//        die;
        // return "<Row>\n" . implode('', $cells) . "</Row>\n";
    }

    /**
     * Creates column objects and initializes them.
     */
    protected function initColumns()
    {
        if (empty($this->columns)) {
            $this->guessColumns();
        }
        foreach ($this->columns as $i => $column) {
            if (is_string($column)) {
                $column = $this->createDataColumn($column);
            } else {
                $column = Yii::createObject(array_merge([
                    'class' => $this->dataColumnClass ? : ExcelColumn::className(),
                    'grid' => $this,
                ], $column));
            }
            if (!$column->visible) {
                unset($this->columns[$i]);
                continue;
            }
            $this->columns[$i] = $column;
        }
    }

    /**
     * Creates a [[DataColumn]] object based on a string in the format of "attribute:format:label".
     * @param string $text the column specification string
     * @return DataColumn the column instance
     * @throws InvalidConfigException if the column specification is invalid
     */
    protected function createDataColumn($text)
    {
        if (!preg_match('/^([^:]+)(:(\w*))?(:(.*))?$/', $text, $matches)) {
            throw new InvalidConfigException('The column must be specified in the format of "attribute", "attribute:format" or "attribute:format:label"');
        }

        return Yii::createObject([
            'class' => $this->dataColumnClass ? : ExcelColumn::className(),
            'grid' => $this,
            'attribute' => $matches[1],
            'format' => isset($matches[3]) ? $matches[3] : 'text',
            'label' => isset($matches[5]) ? $matches[5] : null,
        ]);
    }

    /**
     * This function tries to guess the columns to show from the given data
     * if [[columns]] are not explicitly specified.
     */
    protected function guessColumns()
    {
        $models = $this->dataProvider->getModels();
        $model = reset($models);
        if (is_array($model) || is_object($model)) {
            foreach ($model as $name => $value) {
                $this->columns[] = $name;
            }
        }
    }
}
