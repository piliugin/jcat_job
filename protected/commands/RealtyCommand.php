<?php
/**
 * Консольное приложение для работы с Yandex Realty
 * Пример запуска:
 * php protected/yiic.php realty import --path='/vagrant/protected/data/realty/test.xml'
 * @link http://www.yiiframework.com/doc/guide/1.1/ru/topics.console
 */

class RealtyCommand extends CConsoleCommand {

    /**
     * @var int Счетчик ошибок импорта
     */
    private $errorCnt = 0;

    /**
     * Действие, выполняемое при запуске приложения
     * @param $path string путь к файлу XML
     * @return int Код ошибки (0 - успешно). Соответствует кол-ву неимпортированных предложений.
     */
    public function actionImport($path)
    {

        $reader = new XMLReader();
        $reader->open($path);

        # пропускаем всё до первого offer
        while ($reader->read() && $reader->name !== 'offer');

        # читаем предложения
        while ($reader->name === 'offer')
        {

            # объект SimpleXMLElement, соответствующий одному предложению
            $node = new SimpleXMLElement($reader->readOuterXml());

            # внутренний id предложения
            $offerId = (int) $reader->getAttribute('internal-id');

            /**
             * Работа с предложением
             */
            # создаем модель
            $realtyModel = new Realty();
            # заполняем свойства модели из XML
            $realtyModel->setXmlAttributes($node);

            /**
             * Работа с местоположением
             */
            # создаем модель
            $locationModel = new Location('import');
            # заполняем данные из XML
            $locationModel->setXMLAttributes($node->location);


            /**
             * Работа с метро
             */
            # сведения о метро не обязательные
            if($node->location->metro && $node->location->metro->name->__toString() !== ''){
                # создаем модель
                $metroModel = new Metro('import');
                # заполняем данные из XML
                $metroModel->setXmlAttributes($node->location->metro);
            }

            /**
             * Работа с агентом
             */
            # создаем модель
            $agentModel = new SalesAgent('import');
            # заполняем данные из XML
            $agentModel->setXmlAttributes($node->{'sales-agent'});



            /**
             * Валидация данных
             */
            # если проиошли какие-то ошибки при валидации
            if(
                !$realtyModel->validate() ||
                !$locationModel->validate() ||
                (isset($metroModel) && !$metroModel->validate()) ||
                !$agentModel->validate()
            ){

                $this->errorCnt++;

                echo 'Не удалось сохранить предложение ' . $offerId . PHP_EOL;

                # выводим ошибки валидации предложения, если они есть
                if($realtyModel->hasErrors()){
                    echo 'Ошибки модели предложения:' . PHP_EOL;
                    print_r($realtyModel->getErrors());
                }


                # выводим ошибки валидации местоположения
                if($locationModel->hasErrors()){
                    echo 'Ошибки модели местоположения:' . PHP_EOL;
                    print_r($locationModel->getErrors());
                }

                # выводим ошибки валидации метро
                if(isset($metroModel) && $metroModel->hasErrors()){
                    echo 'Ошибки модели метро:' . PHP_EOL;
                    print_r($metroModel->getErrors());
                }

                # ошибки валидации агента
                if($agentModel->hasErrors()){
                    echo 'Ошибки модели агента' . PHP_EOL;
                    print_r($agentModel->getErrors());
                }

            }
            # сохраняем данные
            else{

                # false - сохранение без предварительной валидации
                $realtyModel->save(false);

                # перед сохранение заполняем привязку к $realtyModel
                $locationModel->realty_id = $realtyModel->id;
                $locationModel->save(false);

                if(isset($metroModel)){
                    $metroModel->location_id = $locationModel->id;
                    $metroModel->save(false);
                }

                $agentModel->realty_id = $realtyModel->id;
                $agentModel->save(false);

                echo 'Предложение '.$offerId.' импортировано' . PHP_EOL;

            }

            $reader->next('offer');
        }

        # код возарата (0 - успешно, остальное - код ошибки)
        return $this->errorCnt;
    }

}