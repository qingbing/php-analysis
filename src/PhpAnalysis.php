<?php
/**
 * @link        http://www.phpcorner.net
 * @author      qingbing<780042175@qq.com>
 * @copyright   Chengdu Qb Technology Co., Ltd.
 */

namespace Zf\PhpAnalysis;


use Zf\Helper\Abstracts\Singleton;
use Zf\Helper\Exceptions\ProgramException;
use Zf\PhpAnalysis\lib\Analysis;

/**
 * 对外工具: 对文章分句、分词、提取关键词
 *
 * Class PhpAnalysis
 * @package Zf\PhpAnalysis
 *
 * @method bool SetSource($source, $source_charset = 'utf-8', $target_charset = 'utf-8') 设置源字符串
 * @method void SetResultType($rstype) 设置结果类型(只在获取finallyResult才有效), $rstype 1 为全部， 2去除特殊符号
 * @method void LoadDict($maindic = '') 载入词典
 * @method bool StartAnalysis($optimize = true) 开始执行分析，$optimize 是否对结果进行优化
 * @method string GetFinallyResult($spword = ' ', $word_meanings = false) 获取文章最终的分词结果字符串(用 $spword 分割)
 * @method array GetSimpleResult() 获取粗分结果，不包含粗分属性
 * @method array GetSimpleResultAll() 获取粗分结果，包含粗分属性（1中文词句、2 ANSI词汇（包括全角），3 ANSI标点符号（包括全角），4数字（包括全角），5 中文标点或无法识别字符）
 * @method array GetFinallyIndex() 获取索引hash数组, array('word' => count, ...)
 * @method void MakeDict($source_file, $target_file = '') 编译词典, $sourcefile utf-8编码的文本词典数据文件(参见范例dict/not-build/base_dic_full.txt), 需要PHP开放足够的内存才能完成操作
 * @method void ExportDict($targetfile) 导出词典的词条, $targetfile 保存位置
 */
class PhpAnalysis extends Singleton
{
    // SetResultType 的类型
    const RESULT_TYPE_ALL    = 1; // 全部
    const RESULT_TYPE_COMMON = 2; // 去除特殊符号
    // GetWordInfos 的类型
    const INFO_TYPE_WORD      = 'word';
    const INFO_TYPE_KEY_GROUP = 'key_groups';
    /**
     * @var Analysis
     */
    protected $analysis;
    /**
     * @var bool 分析文章时是否对结果进行优化
     */
    private $_optimize = true;

    /**
     * @inheritDoc
     */
    protected function init()
    {
        Analysis::$loadInit = false;
        // 实例化
        $this->analysis = new Analysis();
        // 载入词典
        $this->analysis->LoadDict();
    }

    /**
     * 为 Analysis 设置属性
     *
     * @param array|null $properties
     * @param bool $strict
     * @return $this|void
     */
    public function configure(array $properties = null, $strict = false)
    {
        foreach ($properties as $property => $value) {
            // 对象定义了该属性才为属性赋值
            if (property_exists($this->analysis, $property)) {
                $this->analysis->{$property} = $value;
            }
        }
        return $this;
    }

    /**
     * 设置文章分词时是否优化结果，默认true
     *
     * @param bool $optimize
     * @return $this
     */
    public function setOptimize(bool $optimize = true)
    {
        $this->_optimize = $optimize;
        return $this;
    }

    /**
     * 检测某个词是否存在
     *
     * @param string $word
     * @return bool
     */
    public function isWord(string $word): bool
    {
        return $this->analysis->IsWord($this->toUnicode($word));
    }

    /**
     * 获得某个词的词性及词频信息
     *
     * @param string $word
     * @return string
     */
    public function getWordProperty(string $word): string
    {
        return $this->analysis->GetWordProperty($this->toUnicode($word));
    }

    /**
     * 从文件获得词的信息
     *
     * @param string $key
     * @param string $type
     * @return false|array
     */
    public function getWordInfos(string $key, $type = 'word')
    {
        $res = $this->analysis->GetWordInfos($this->toUnicode($key), $type);
        if ($type !== self::INFO_TYPE_KEY_GROUP) {
            return $res;
        }
        $R = [];
        foreach ($res as $key => $re) {
            $R[$this->toString($key)] = $re;
        }
        return $R;
    }

    /**
     * 指定某词的词性信息（通常是新词）
     *
     * @param string $word
     * @param array $infos ['c' => 词频, 'm' => 词性]
     */
    public function setWordInfos(string $word, array $infos)
    {
        $this->analysis->SetWordInfos($this->toUnicode($word), $infos);
    }

    /**
     * 解析文本
     * @param string $content
     * @return $this
     */
    public function start(string $content)
    {
        $this->analysis->SetSource($content);
        $this->analysis->StartAnalysis($this->_optimize);
        return $this;
    }

    /**
     * 获取最终提取的关键字
     *
     * @param int $num 提取关键词的个数
     * @return array
     */
    public function getFinallyKeywords(int $num = 10): array
    {
        return explode(",", $this->analysis->GetFinallyKeywords(--$num));
    }

    /**
     * 将字符串转换成unicode
     *
     * @param string $string
     * @return string
     */
    protected function toUnicode(string $string): string
    {
        $sourceCharset = $this->analysis->sourceCharSet;
        if (preg_match("/^utf/", $sourceCharset)) {
            return iconv('utf-8', UCS2, $string);
        } else if (preg_match("/^gb/", $sourceCharset)) {
            return iconv('utf-8', UCS2, iconv('gb18030', 'utf-8', $string));
        } else if (preg_match("/^big/", $sourceCharset)) {
            return iconv('utf-8', UCS2, iconv('big5', 'utf-8', $string));
        }
        return $string;
    }

    /**
     * 将unicode转换成字符串
     *
     * @param string $string
     * @return string
     */
    protected function toString(string $string): string
    {
        $sourceCharset = $this->analysis->sourceCharSet;
        if (preg_match("/^utf/", $sourceCharset)) {
            return iconv(UCS2, 'utf-8', $string);
        } else if (preg_match("/^gb/", $sourceCharset)) {
            return iconv('utf-8', 'gb18030', iconv(UCS2, 'utf-8', $string));
        } else if (preg_match("/^big/", $sourceCharset)) {
            return iconv('utf-8', 'big5', iconv(UCS2, 'utf-8', $string));
        }
        return $string;
    }

    /**
     * 不存在的函数可以直接透传到 \Zf\PhpAnalysis\lib\Analysis
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws ProgramException
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->analysis, $name)) {
            return call_user_func_array([$this->analysis, $name], $arguments);
        }
        throw new ProgramException("调用不存在方法");
    }
}
