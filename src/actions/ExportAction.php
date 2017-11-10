<?php

namespace hipanel\actions;

use Yii;
use hiqdev\yii2\export\exporters\Type;
use hiqdev\yii2\export\models\CsvSettings;
use hiqdev\yii2\export\models\TsvSettings;
use hiqdev\yii2\export\models\XlsxSettings;
use hiqdev\yii2\export\exporters\ExporterFactoryInterface;

class ExportAction extends IndexAction
{
    /**
     * @var ExporterFactoryInterface
     */
    private $exporterFactory;

    public function __construct($id, $controller, ExporterFactoryInterface $exporterFactory)
    {
        parent::__construct($id, $controller);
        $this->exporterFactory = $exporterFactory;
    }

    public function run()
    {
        $type = $this->getType();
        $exporter = $this->exporterFactory->build($type);
        $settings = $this->loadSettings($type);
        if ($settings !== null) {
            $settings->applyTo($exporter);
        }
        $representation = $this->ensureRepresentationCollection()->getByName($this->controller->indexPageUiOptionsModel->representation);
        $columns = $representation->getColumns();
        $gridClassName = $this->guessGridClassName();
        $grid = new $gridClassName(['dataProvider' => $this->getDataProvider()]);
        $grid->dataColumnClass = \hiqdev\higrid\DataColumn::class;
        $result = $exporter->export($grid, $columns);
        $filename = $exporter->filename . '.' . $type;

        return Yii::$app->response->sendContentAsFile($result, $filename);
    }

    public function loadSettings($type)
    {
        $map = [
            Type::CSV => CsvSettings::class,
            Type::TSV => TsvSettings::class,
            Type::XLSX => XlsxSettings::class,
        ];

        $settings = Yii::createObject($map[$type]);
        if ($settings->load(Yii::$app->request->get(), '') && $settings->validate()) {
            return $settings;
        }

        return null;
    }

    protected function getType()
    {
        return Yii::$app->request->get('format');
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function guessGridClassName()
    {
        $moduleName = $this->controller->module->id;
        $controllerName = $this->controller->id;
        $girdClassName = sprintf('\hipanel\modules\%s\grid\%sGridView', $moduleName, ucfirst($controllerName));
        if (class_exists($girdClassName)) {
            return $girdClassName;
        }

        throw new \Exception("ExportAction cannot find a {$girdClassName}");
    }
}