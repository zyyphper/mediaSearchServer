<?php

namespace App\Helpers;

use Carbon\Carbon;
use FFMpeg\FFProbe;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Tools
{
    // 加密串
    const ENCRYPT_KEY = 'rgB5N8B+DOSoZA10jmcAR6Eg3pPYj950';

    /**
     * 写入成功返回
     * @param string $message
     * @return array
     */
    public static function success($message = '写入成功', $code = 0, $data = [])
    {
        if (defined('ERROR_CODE')) $code = ERROR_CODE;
        $response = [
            'code' => $code,
            'message' => $message
        ];
        if (!empty($data)) $response = array_merge($response, ['data' => $data]);
        return $response;
    }

    /**
     * 写入成功返回
     * @param array $data
     * @param int $code
     * @return array
     */
    public static function setData($data = [], $code = 0)
    {
        if (defined('ERROR_CODE')) $code = ERROR_CODE;
        $response = [
            'message' => '',
            'code' => $code,
            'data' => $data
        ];
        return $response;
    }

    /**
     * 写入失败返回
     * @param string $message
     * @param int $code
     * @return array
     */
    public static function error($message = '写入失败', $code = 1, $data = [])
    {
        if (defined('ERROR_CODE')) $code = ERROR_CODE;
        $response = [
            'code' => $code,
            'message' => $message
        ];
        if (!empty($data)) $response = array_merge($response, ['data' => $data]);
        return $response;
    }

    /**
     * 设置日志文件名
     * @param $fileName
     * @param $bugLevel
     * @return Logger
     * @throws \Exception
     */
    public static function setFileName($fileName, $bugLevel)
    {
        if (!env('DEFINING_LOG_FILE_ON', true)) $fileName = 'laravel_custom';
        $stream = new StreamHandler(storage_path('logs/' . date('Y/m/d') . '/' . $fileName . '.log'), $bugLevel);
        $stream->setFormatter(new LineFormatter(null, null, true, true));
        $log = new Logger($fileName);
        $log->pushHandler($stream);

        return $log;
    }

    /**
     * 单个日志输出
     * @param $content
     * @throws \Exception
     */
    public static function logInfo($content, $title = null, $fileName = 'laravel_custom')
    {
        if (env('LOG_ON', true)) {
            $log = self::setFileName($fileName, Logger::INFO);
            if ($title) $log->info($title);
            $log->info('==========================');
            $log->info(print_r($content, true));
            $log->info('==========================');
        }
    }

    /**
     * 单个错误日志输出
     * @param string $content
     * @throws \Exception
     */
    public static function logError($content, $title = null, $fileName = 'laravel_custom')
    {
        $log = self::setFileName($fileName, Logger::ERROR);
        if ($title) $log->info($title);
        $log->error('**************************');
        $log->error($content);
        $log->error('**************************');
    }

    /**
     * 事务异常错误日志输出
     * @param $exception
     * @throws \Exception
     */
    public static function logUnusualError($exception, $title = null, $fileName = 'laravel_custom')
    {
        $log = self::setFileName($fileName, Logger::ERROR);
        if ($title) $log->info($title);
        $log->error('**************************');
        $log->error("\n"
            . "----------------------------------------\n"
            . "| 错误信息 | {$exception->getMessage()}\n"
            . "| 文件路径 | {$exception->getFile()} (第{$exception->getLine()}行)\n"
            . "| 访问路径 | [" . request()->method() . "] " . request()->url() . "\n"
            . "| 请求参数 | " . json_encode(request()->all()) . "\n"
            . "----------------------------------------\n");
        $log->error('**************************');
    }

    /**
     * 多个日志一次性输出
     * @param $content
     * @param null $title
     * @param bool $isEnd
     * @param string $fileName
     * @return bool
     * @throws \Exception
     */
    public static function singleLog($content, $title = null, $isEnd = false, $fileName = 'laravel_custom')
    {
        if (!isset($GLOBALS['debugArray'])) {
            $GLOBALS['debugArray'] = array();
        }

        if ($title) {
            array_push($GLOBALS['debugArray'], $title);
            array_push($GLOBALS['debugArray'], '==========================');
        }

        if ($content) {
            array_push($GLOBALS['debugArray'], print_r($content, true));
            array_push($GLOBALS['debugArray'], '--------------------------');
        }

        if ($isEnd) {
            self::logInfo($GLOBALS['debugArray'], null, $fileName);
            unset($GLOBALS['debugArray']);
        }

        return true;
    }

    /**
     * 异步日志
     * @param $keyName
     * @param $content
     * @param null $title
     * @param bool $isEnd
     * @param string $fileName
     * @return bool
     * @throws \Exception
     */
    public static function asyncLog($keyName, $content, $title = null, $isEnd = false, $fileName = 'laravel_custom')
    {
        if (!isset($GLOBALS[$keyName])) {
            $GLOBALS[$keyName] = array();
        }

        if ($title) {
            array_push($GLOBALS[$keyName], $title);
            array_push($GLOBALS[$keyName], '==========================');
        }

        if ($content) {
            array_push($GLOBALS[$keyName], print_r($content, true));
            array_push($GLOBALS[$keyName], '--------------------------');
        }

        if ($isEnd) {
            self::logInfo($GLOBALS[$keyName], null, $fileName);
            unset($GLOBALS[$keyName]);
        }

        return true;
    }

    /**
     * curl请求
     * @param string $url 访问的URL
     * @param string $post post数据(不填则为GET)
     * @param string $cookie 提交的$cookies
     * @param int $returnCookie 是否返回$cookies
     * @return mixed|string
     */
    public static function curlRequest($url, $post = '', $contentType = 'application/json', $cookie = '', $returnCookie = 0)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");

        if (is_array($post)) {
            // 数组类型
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        } else if ($post) {
            // json类型
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                "Content-Type: $contentType",
                'Content-Length: ' . strlen($post)
            ));
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }

        if ($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if ($returnCookie) {
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie'] = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        } else {
            return $data;
        }
    }

    /**
     * 检查请求参数
     * @param $keys
     * @param bool $isOnly
     * @return array|\Illuminate\Http\Request|\Laravel\laravel_custom\Application|mixed|string
     * @throws RequestException
     */
    public static function checkRequest($keys, $isOnly = true)
    {
        // 判断是否是数组
        if (!is_array($keys)) {
            $required[] = $keys;
        } else {
            $required = $keys;
        }
        // 检查必传参数
        $allRequest = request()->keys();
        foreach ($required as $requiredKey) {
            if (!in_array($requiredKey, $allRequest)) {
                $withoutKeys[] = $requiredKey;
            }
        }

        // 拼接错误参数
        if (!empty($withoutKeys)) {
            $message = '缺少参数';
            if (env('APP_ENV') != 'master') {
                $message .= ':' . implode(',', $withoutKeys);
            }
            throw new RequestException($message);
        }

        if ($isOnly) {
            return request($required);
        } else {
            return request()->all();
        }
    }

    /**
     * 保留两位小数
     * @param $price
     * @return string|bool
     */
    public static function formatPrice($price, $trimZero = true)
    {
        if ($trimZero) {
            return (string)floatval(substr(sprintf("%.3f", $price), 0, -1));
        }
        return substr(sprintf("%.3f", $price), 0, -1);
    }

    /**
     * 加密
     * @param $data
     * @return mixed
     */
    public static function dataEncrypt($data)
    {
        $key = self::ENCRYPT_KEY;
        ksort($data);
        return md5(http_build_query($data) . $key);
    }

    /**
     * 传参验证
     * @param array $data 需要验证的数组
     * @param array $rules 验证规则
     * @param string $messageKey 使用哪个板块的验证提示
     * @throws \Exception
     */
    public static function dataValidator($data, $rules, $messageKey)
    {
        if (is_array($data)) {
            $messages = config("validator_message." . $messageKey);
            $validator = Validator::make($data, $rules, $messages);
            if ($validator->fails()) {
                $errorMessage = json_decode($validator->errors(), true);
                throw new ValidationException(array_first($errorMessage)[0] ?? '验证数据失败');
            }
        }
    }

    /**
     * 模拟生成token
     * @return string
     */
    public static function setToken()
    {
        // 生成一个不会重复的字符串
        $str = md5(uniqid(md5(microtime(true)), true));
        $str = sha1($str);
        return $str;
    }

    /**
     * 过滤掉EmoJi表情
     * @param $str
     * @return mixed
     */
    public static function filterEmoJi($str)
    {
        $str = preg_replace_callback('/./u', function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        }, $str);

        return $str ?? '?';
    }

    /**
     * cos curl请求
     * @param        $url
     * @param string $method
     * @param array $header
     * @param array $body
     * @return mixed
     */
    public static function requestWithHeader($url, $method = 'POST', $header = array(), $body = array())
    {
        //array_push($header, 'Accept:application/json');
        //array_push($header, 'Content-Type:application/json');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        switch ($method) {
            case "GET" :
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case "POST" :
                curl_setopt($ch, CURLOPT_POST, true);
                break;
            case "PUT" :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case "DELETE" :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }
        curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        if (isset($body{3}) > 0) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        if (count($header) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $ret = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($ret, true);

        return $data;
    }

    /**
     * 远程下载图片
     * @param        $imageUrl
     * @param        $imageName
     * @return string
     */
    public static function curlDownPic($imageUrl, $imageName = '')
    {
        $uploadDir = '/uploads/temp/';

        // 文件保存目录
        $fileDir = public_path() . $uploadDir;

        if (!is_dir($fileDir)) @mkdir($fileDir, 755, true);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $imageUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);
        $filename = $fileDir . time() . ($imageName ?: pathinfo($imageUrl, PATHINFO_BASENAME)) . '.png';
        $resource = fopen($filename, 'a');
        fwrite($resource, $file);
        fclose($resource);
        return $filename;
    }

    /**
     * 生成订单编号
     * @param string $type C用户订单、P交易单、W提现单
     * @return string
     */
    public static function createOrderNumber($type = 'C')
    {
        // 当前年月日时分秒
        $year = substr(date('Y'), 2, 2);
        $orderNumber = $type . $year . date('mdHis');

        // 拼上4位随机数
        $orderNumber .= rand(1000, 9999);
        return self::checkOrderNumber($orderNumber, $type);
    }


    /**
     * 时间对象转化
     * @param $timeObj
     * @return string
     */
    public static function formatTime($timeObj, $format = 'Y-m-d H:i:s')
    {
        if (!$timeObj) return '';
        return Carbon::parse($timeObj)->format($format);
    }


    /**
     * 转换为时间区间
     * @param        $date
     * @param string $startTime
     * @param string $endTime
     * @return array
     */
    public static function getBetweenTime($date, $startTime = '', $endTime = '')
    {
        switch ($date) {
            case 'today':
                // 今天
                return [Carbon::today(), Carbon::tomorrow()->subSecond(1)];
            case 'yesterday':
                // 昨天
                return [Carbon::yesterday(), Carbon::today()->subSecond(1)];
            case 'week':
                // 7天
                return [Carbon::yesterday()->subDay(6), Carbon::today()->subSecond(1)];
            case 'month':
                // 30天
                return [Carbon::yesterday()->subDay(29), Carbon::today()->subSecond(1)];
            case 'season':
                // 90天
                return [Carbon::yesterday()->subDay(89), Carbon::today()->subSecond(1)];
            default:
                if (!empty($startTime) && !empty($endTime)) {
                    $startTime .= ' 00:00:00';
                    $endTime .= ' 23:59:59';
                }
                $startTime = Carbon::parse($startTime);
                $endTime = Carbon::parse($endTime);
                return [$startTime, $endTime];
        }
    }

    /**
     * 阿拉伯数字转汉字
     * @param int $number 数字
     * @param bool $isRmb 是否是金额数据
     * @return string
     */
    public static function number2chinese($number, $isRmb = false)
    {
        // 判断正确数字
        if (!preg_match('/^-?\d+(\.\d+)?$/', $number)) {
            return 'number2chinese() wrong number';
        }
        list($integer, $decimal) = explode('.', $number . '.0');

        // 检测是否为负数
        $symbol = '';
        if (substr($integer, 0, 1) == '-') {
            $symbol = '负';
            $integer = substr($integer, 1);
        }
        if (preg_match('/^-?\d+$/', $number)) {
            $decimal = null;
        }
        $integer = ltrim($integer, '0');

        // 准备参数
        $numArr = ['', '一', '二', '三', '四', '五', '六', '七', '八', '九', '.' => '点'];
        $descArr = ['', '十', '百', '千', '万', '十', '百', '千', '亿', '十', '百', '千', '万亿', '十', '百', '千', '兆', '十', '百', '千'];
        if ($isRmb) {
            $number = substr(sprintf("%.5f", $number), 0, -1);
            $numArr = ['', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖', '.' => '点'];
            $descArr = ['', '拾', '佰', '仟', '万', '拾', '佰', '仟', '亿', '拾', '佰', '仟', '万亿', '拾', '佰', '仟', '兆', '拾', '佰', '仟'];
            $rmbDescArr = ['角', '分', '厘', '毫'];
        }

        // 整数部分拼接
        $integerRes = '';
        $count = strlen($integer);
        if ($count > max(array_keys($descArr))) {
            return 'number2chinese() number too large.';
        } else if ($count == 0) {
            $integerRes = '零';
        } else {
            for ($i = 0; $i < $count; $i++) {
                $n = $integer[$i];      // 位上的数
                $j = $count - $i - 1;   // 单位数组 $descArr 的第几位
                // 零零的读法
                $isLing = $i > 1                    // 去除首位
                    && $n !== '0'                   // 本位数字不是零
                    && $integer[$i - 1] === '0';    // 上一位是零
                $cnZero = $isLing ? '零' : '';
                $cnNum = $numArr[$n];
                // 单位读法
                $isEmptyDanwei = ($n == '0' && $j % 4 != 0)     // 是零且一断位上
                    || substr($integer, $i - 3, 4) === '0000';  // 四个连续0
                $descMark = isset($cnDesc) ? $cnDesc : '';
                $cnDesc = $isEmptyDanwei ? '' : $descArr[$j];
                // 第一位是一十
                if ($i == 0 && $cnNum == '一' && $cnDesc == '十') $cnNum = '';
                // 二两的读法
                $isChangeEr = $n > 1 && $cnNum == '二'       // 去除首位
                    && !in_array($cnDesc, ['', '十', '百'])  // 不读两\两十\两百
                    && $descMark !== '十';                   // 不读十两
                if ($isChangeEr) $cnNum = '两';
                $integerRes .= $cnZero . $cnNum . $cnDesc;
            }
        }

        // 小数部分拼接
        $decimalRes = '';
        $count = strlen($decimal);
        if ($decimal === null) {
            $decimalRes = $isRmb ? '整' : '';
        } else if ($decimal === '0') {
            $decimalRes = '零';
        } else if ($count > max(array_keys($descArr))) {
            return 'number2chinese() number too large.';
        } else {
            for ($i = 0; $i < $count; $i++) {
                if ($isRmb && $i > count($rmbDescArr) - 1) break;
                $n = $decimal[$i];
                $cnZero = $n === '0' ? '零' : '';
                $cnNum = $numArr[$n];
                $cnDesc = $isRmb ? $rmbDescArr[$i] : '';
                $decimalRes .= $cnZero . $cnNum . $cnDesc;
            }
        }
        // 拼接结果
        $res = $symbol . ($isRmb ?
                $integerRes . ($decimalRes === '零' ? '元整' : "元$decimalRes") :
                $integerRes . ($decimalRes === '' ? '' : "点$decimalRes"));
        return $res;
    }

    /**
     * 距离处理
     * @param $distance
     * @return string
     */
    public static function dealDistance($distance)
    {
        if (!$distance) {
            $distance = '';
        } else if ($distance > 1000) {
            $distance = substr(sprintf("%.3f", ($distance / 1000)), 0, -1) . '千米';
        } else {
            $distance = substr(sprintf("%.3f", $distance), 0, -1) . '米';
        }
        return $distance;
    }

    /**
     * 去掉小数00
     * @param int $price
     * @return string
     */
    public static function fatPrice($price = 0)
    {
        $price = sprintf('%.2f', $price);
        return rtrim(rtrim($price, '0'), '.');
    }

    /**
     * 获取外网IP
     * @return string
     */
    public static function getClientIp()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (!empty($_SERVER["REMOTE_ADDR"])) {
            $cip = $_SERVER["REMOTE_ADDR"];
        }
        return $cip ?? '';
    }

    /**
     * 精度计算（最多只支持4位小数计算）
     * @param $m
     * @param $n
     * @param $x
     * @return float|int|string
     */
    public static function calc($m, $n, $x)
    {
        $numCountM = 0;
        $numCountN = 0;
        $tempM = explode('.', $m);
        if (sizeof($tempM) > 1) {
            $decimal = end($tempM);
            $numCountM = strlen($decimal);
        }

        $tempN = explode('.', $n);
        if (sizeof($tempN) > 1) {
            $decimal = end($tempN);
            $numCountN = strlen($decimal);
        }

        if (($numCountM ?? 0) > ($numCountN ?? 0)) {
            $baseNum = pow(10, $numCountM ?? 0);
        } else {
            $baseNum = pow(10, $numCountN ?? 0);
        }

        $m = intval(round($m * $baseNum));
        $n = intval(round($n * $baseNum));

        switch ($x) {
            case '+':
                $response = $m + $n;
                break;
            case '-':
                $response = $m - $n;
                break;
            case '*':
                $response = $m * $n / $baseNum;
                break;
            case '/':
                if ($n != 0) {
                    $response = $m / $n;
                } else {
                    $response = '被除数不能为零';
                }
                break;
            default:
                $response = '参数传递错误';
                break;
        }
        return $response / $baseNum;
    }

    /**
     * 文件转base64
     * @param $file
     * @return string
     */
    public static function fileToBase64($file)
    {
        $base64File = '';
        if (file_exists($file)) {
            $mimeType = mime_content_type($file) ?: 'image/png';
            $base64Data = base64_encode(file_get_contents($file));
            $base64File = 'data:' . $mimeType . ';base64,' . $base64Data;
        }
        return $base64File;
    }

    /**
     * 格式化整形
     * @param $value
     * @return int
     */
    public static function formatInt($value)
    {
        return (int)$value;
    }

    /**
     * 转换URL链接HTTP为HTTPS
     * @param $url
     * @return mixed
     */
    public static function httpToHttps($url)
    {
        if (empty($url)) return $url;
        $url = trim($url);
        $prefix = substr($url, 0, 7);
        if (strtolower($prefix) == 'http://') {
            $url = 'https://' . substr($url, 7);
        }
        return $url;
    }

    /**
     * 日转换为星期
     * @param $day
     * @return mixed
     */
    public static function dayOfWeek($day)
    {
        if (!$day) return $day;
        $weekNum = Carbon::parse($day)->dayOfWeek;
        switch ($weekNum) {
            case '0':
                $weekCn = '星期日';
                break;
            case '1':
                $weekCn = '星期一';
                break;
            case '2':
                $weekCn = '星期二';
                break;
            case '3':
                $weekCn = '星期三';
                break;
            case '4':
                $weekCn = '星期四';
                break;
            case '5':
                $weekCn = '星期五';
                break;
            case '6':
                $weekCn = '星期六';
                break;
            default:
                $weekCn = '未知';
                break;
        }
        return $weekCn;
    }

    /**
     * 秒转化为时分秒格式
     * @param $seconds
     * @return string
     */
    public static function changeTimeType($seconds)
    {
        if ($seconds < 1 || !$seconds) {
            return '00:00:00';
        } elseif ($seconds > 3600) {
            $hours = str_pad(intval($seconds / 3600), 2, 0, STR_PAD_LEFT);
            $time = $hours . ":" . gmstrftime('%M:%S', $seconds);
        } else {
            $time = gmstrftime('%H:%M:%S', $seconds);
        }
        return $time;
    }

    public static function dirNotExistMkdir($path)
    {
        if(is_dir($path)) {
            return true;
        }
        return mkdir($path);
    }



    public static function getMP4Info($path)
    {
        $ffprobe = FFProbe::create(config('ffmpeg'));
        $fileTime = $ffprobe->streams($path)
            ->videos()
            ->first()
            ->get('duration');
        $width = $ffprobe->streams($path)
            ->videos()
            ->first()
            ->get('width');
        $height = $ffprobe->streams($path)
            ->videos()
            ->first()
            ->get('height');
        $fileSize = $ffprobe->format($path)
            ->get('size') / 1024 /1024;
        return [
            'file_time' => intval($fileTime),
            'file_size' => intval($fileSize),
            'file_resolution' => $width .'*'. $height
        ];
    }


    /**
     * 转换接收的XML
     * @param $xmlStr
     * @return array
     */
    public static function dealXmlData($xmlStr)
    {
        $newData = [];
        $parser = xml_parser_create();
        xml_parse_into_struct($parser, $xmlStr, $parserValue, $index);
        xml_parser_free($parser);
        foreach ($parserValue as $val) {
            $newData[$val['tag']] = $val['value'] ?? '';
        }
        return $newData;
    }

    public static function dealXmlDataByDOC($xmlStr)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xmlStr);
        $node = $doc->documentElement;



        return self::getArray($node);
    }

    public static function getArray($node) {
        $array = false;

        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $array[$attr->nodeName] = $attr->nodeValue;
            }
        }

        if ($node->hasChildNodes()) {
            if ($node->childNodes->length == 1) {
                $array[$node->firstChild->nodeName] = self::getArray($node->firstChild);
            } else {
                foreach ($node->childNodes as $childNode) {
                    if ($childNode->nodeType != XML_TEXT_NODE) {
                        $array[$childNode->nodeName][] = self::getArray($childNode);
                    }
                }
            }
        } else {
            return $node->nodeValue;
        }
        return $array;
    }

    public static function getFileInfo($url)
    {
        $path = parse_url($url)['path'];
        return pathinfo($path);
    }

    public static function spaceCapacityUnitConvert($num,$unit,$needUnit)
    {
        $unitMap  = ['B','KB','MB','GB','TB'];
        if (!in_array($unit,$unitMap) || !in_array($needUnit,$unitMap)) {
            throw new \Exception("非法存储单位");
        }
        $unitMap = array_flip($unitMap);
        if ($unitMap[$needUnit] == $unitMap[$unit]) return $num;
        if ($unitMap[$needUnit] > $unitMap[$unit]) {
            return round($num / pow(1024,$unitMap[$needUnit]-$unitMap[$unit]+1),2);
        }
        return round($num * pow(1024,$unitMap[$unit]-$unitMap[$needUnit]+1),2);
    }
}
