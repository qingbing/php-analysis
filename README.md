# php-analysis
文本内容分词、文章关键字提取

# 该组件封装了 phpAnalysis

# 使用示例

```php
<?php
use Zf\PhpAnalysis\PhpAnalysis;

$analysis = PhpAnalysis::getInstance()
    ->configure([
        'sourceCharSet' => 'utf-8',
        'targetCharSet' => 'utf-8',
        'resultType'    => 1,
    ]);


// 设置源字符串(文章)
$res = $analysis->SetSource('中国人是好样的');
var_dump($res);

// 设置源字符串(文章)
$analysis->SetResultType(PhpAnalysis::RESULT_TYPE_ALL); // NULL, 全部
$analysis->SetResultType(PhpAnalysis::RESULT_TYPE_COMMON); // NULL, 去除特殊符号

// 载入词典，该函数一般不调用，在init中已经调用
$analysis->LoadDict(); // NULL

// 检查是否在词库
$res = $analysis->isWord("中国");
var_dump($res);

// 获得某个词的词性及词频信息
$res = $analysis->getWordProperty("劝导");
var_dump($res);

// 获得词的信息
$res = $analysis->getWordInfos("劝导", PhpAnalysis::INFO_TYPE_KEY_GROUP);
var_dump($res);
$res = $analysis->getWordInfos("中国");
var_dump($res);

// 指定某词的词性信息（通常是新词）
$res = $analysis->setWordInfos("中国", ['c' => 4, 'm' => 'n1']);
var_dump($res);

// 导出词典的词条到文件中
//$analysis->ExportDict("xx.txt"); // NULL, 导出词典的词条到xx.txt文件

// 开始解析文章, 相当于 $analysis->setSource + $analysis->StartAnalysis
$analysis->start(<<<EDO
七.各自回家
漂亮小妞:“奇怪,我真的好想发条信息去骂他.“ 
周星星:““呵呵.她不发信息骂我才奇怪.“ 
漂亮小妞:“完蛋了，难道我真的喜欢那个无赖了?“ 
周星星:“嘿嘿，她不喜欢我这个无赖那才叫完蛋.
EDO
);
// 获取粗分结果，不包含粗分属性
$res = $analysis->GetSimpleResult();
var_dump($res);
// 获取粗分结果，包含粗分属性（1中文词句、2 ANSI词汇（包括全角），3 ANSI标点符号（包括全角），4数字（包括全角），5 中文标点或无法识别字符）
$res = $analysis->GetSimpleResultAll();
var_dump($res);
// 获取索引hash数组, array('word' => count, ...)
$res = $analysis->GetFinallyIndex();
var_dump($res);
// 获取最终结果字符串（用空格分开后的分词结果）
$res = $analysis->GetFinallyResult("|", true);
var_dump($res);
// 获取最终关键字(返回用 "," 间隔的关键字)
$res = $analysis->GetFinallyKeywords(3);
var_dump($res);
?>
```

# 示例响应
```text

bool(true)
bool(true)
string(4) "/vn7"
array(3) {
  ["劝导"]=>
  array(2) {
    [0]=>
    string(1) "7"
    [1]=>
    string(2) "vn"
  }
  ["埃莫"]=>
  array(2) {
    [0]=>
    string(1) "6"
    [1]=>
    string(2) "nr"
  }
  ["毒理学"]=>
  array(2) {
    [0]=>
    string(1) "2"
    [1]=>
    string(1) "n"
  }
}
array(2) {
  [0]=>
  string(1) "3"
  [1]=>
  string(1) "n"
}
NULL
array(35) {
  [0]=>
  string(3) "七"
  [1]=>
  string(1) "."
  [2]=>
  string(12) "各自回家"
  [3]=>
  string(12) "漂亮小妞"
  [4]=>
  string(1) ":"
  [5]=>
  string(3) "“"
  [6]=>
  string(6) "奇怪"
  [7]=>
  string(1) ","
  [8]=>
  string(36) "我真的好想发条信息去骂他"
  [9]=>
  string(1) "."
  [10]=>
  string(3) "“"
  [11]=>
  string(9) "周星星"
  [12]=>
  string(1) ":"
  [13]=>
  string(3) "“"
  [14]=>
  string(3) "“"
  [15]=>
  string(6) "呵呵"
  [16]=>
  string(1) "."
  [17]=>
  string(30) "她不发信息骂我才奇怪"
  [18]=>
  string(1) "."
  [19]=>
  string(3) "“"
  [20]=>
  string(12) "漂亮小妞"
  [21]=>
  string(1) ":"
  [22]=>
  string(3) "“"
  [23]=>
  string(9) "完蛋了"
  [24]=>
  string(1) ","
  [25]=>
  string(36) "难道我真的喜欢那个无赖了"
  [26]=>
  string(1) "?"
  [27]=>
  string(3) "“"
  [28]=>
  string(9) "周星星"
  [29]=>
  string(1) ":"
  [30]=>
  string(3) "“"
  [31]=>
  string(6) "嘿嘿"
  [32]=>
  string(1) ","
  [33]=>
  string(42) "她不喜欢我这个无赖那才叫完蛋"
  [34]=>
  string(1) "."
}
array(35) {
  [0]=>
  array(2) {
    ["w"]=>
    string(3) "七"
    ["t"]=>
    int(1)
  }
  [1]=>
  array(2) {
    ["w"]=>
    string(1) "."
    ["t"]=>
    int(4)
  }
  [2]=>
  array(2) {
    ["w"]=>
    string(12) "各自回家"
    ["t"]=>
    int(1)
  }
  [3]=>
  array(2) {
    ["w"]=>
    string(12) "漂亮小妞"
    ["t"]=>
    int(1)
  }
  [4]=>
  array(2) {
    ["w"]=>
    string(1) ":"
    ["t"]=>
    int(3)
  }
  [5]=>
  array(2) {
    ["w"]=>
    string(3) "“"
    ["t"]=>
    int(5)
  }
  [6]=>
  array(2) {
    ["w"]=>
    string(6) "奇怪"
    ["t"]=>
    int(1)
  }
  [7]=>
  array(2) {
    ["w"]=>
    string(1) ","
    ["t"]=>
    int(3)
  }
  [8]=>
  array(2) {
    ["w"]=>
    string(36) "我真的好想发条信息去骂他"
    ["t"]=>
    int(1)
  }
  [9]=>
  array(2) {
    ["w"]=>
    string(1) "."
    ["t"]=>
    int(4)
  }
  [10]=>
  array(2) {
    ["w"]=>
    string(3) "“"
    ["t"]=>
    int(5)
  }
  [12]=>
  array(2) {
    ["w"]=>
    string(9) "周星星"
    ["t"]=>
    int(1)
  }
  [13]=>
  array(2) {
    ["w"]=>
    string(1) ":"
    ["t"]=>
    int(3)
  }
  [14]=>
  array(2) {
    ["w"]=>
    string(3) "“"
    ["t"]=>
    int(5)
  }
  [15]=>
  array(2) {
    ["w"]=>
    string(3) "“"
    ["t"]=>
    int(5)
  }
  [16]=>
  array(2) {
    ["w"]=>
    string(6) "呵呵"
    ["t"]=>
    int(1)
  }
  [17]=>
  array(2) {
    ["w"]=>
    string(1) "."
    ["t"]=>
    int(4)
  }
  [18]=>
  array(2) {
    ["w"]=>
    string(30) "她不发信息骂我才奇怪"
    ["t"]=>
    int(1)
  }
  [19]=>
  array(2) {
    ["w"]=>
    string(1) "."
    ["t"]=>
    int(4)
  }
  [20]=>
  array(2) {
    ["w"]=>
    string(3) "“"
    ["t"]=>
    int(5)
  }
  [22]=>
  array(2) {
    ["w"]=>
    string(12) "漂亮小妞"
    ["t"]=>
    int(1)
  }
  [23]=>
  array(2) {
    ["w"]=>
    string(1) ":"
    ["t"]=>
    int(3)
  }
  [24]=>
  array(2) {
    ["w"]=>
    string(3) "“"
    ["t"]=>
    int(5)
  }
  [25]=>
  array(2) {
    ["w"]=>
    string(9) "完蛋了"
    ["t"]=>
    int(1)
  }
  [26]=>
  array(2) {
    ["w"]=>
    string(1) ","
    ["t"]=>
    int(3)
  }
  [27]=>
  array(2) {
    ["w"]=>
    string(36) "难道我真的喜欢那个无赖了"
    ["t"]=>
    int(1)
  }
  [28]=>
  array(2) {
    ["w"]=>
    string(1) "?"
    ["t"]=>
    int(3)
  }
  [29]=>
  array(2) {
    ["w"]=>
    string(3) "“"
    ["t"]=>
    int(5)
  }
  [31]=>
  array(2) {
    ["w"]=>
    string(9) "周星星"
    ["t"]=>
    int(1)
  }
  [32]=>
  array(2) {
    ["w"]=>
    string(1) ":"
    ["t"]=>
    int(3)
  }
  [33]=>
  array(2) {
    ["w"]=>
    string(3) "“"
    ["t"]=>
    int(5)
  }
  [34]=>
  array(2) {
    ["w"]=>
    string(6) "嘿嘿"
    ["t"]=>
    int(1)
  }
  [35]=>
  array(2) {
    ["w"]=>
    string(1) ","
    ["t"]=>
    int(3)
  }
  [36]=>
  array(2) {
    ["w"]=>
    string(42) "她不喜欢我这个无赖那才叫完蛋"
    ["t"]=>
    int(1)
  }
  [37]=>
  array(2) {
    ["w"]=>
    string(1) "."
    ["t"]=>
    int(4)
  }
}
array(36) {
  ["."]=>
  int(5)
  ["我"]=>
  int(3)
  [")"]=>
  int(2)
  ["周星星"]=>
  int(2)
  ["她"]=>
  int(2)
  ["无赖"]=>
  int(2)
  ["星星"]=>
  int(2)
  ["周"]=>
  int(2)
  ["("]=>
  int(2)
  ["信息"]=>
  int(2)
  ["奇怪"]=>
  int(2)
  ["真的"]=>
  int(2)
  ["小妞"]=>
  int(2)
  ["漂亮"]=>
  int(2)
  ["不喜欢"]=>
  int(1)
  ["难道"]=>
  int(1)
  ["嘿嘿"]=>
  int(1)
  ["了"]=>
  int(1)
  ["这个"]=>
  int(1)
  ["那才"]=>
  int(1)
  ["叫"]=>
  int(1)
  ["那个"]=>
  int(1)
  ["喜欢"]=>
  int(1)
  ["七"]=>
  int(1)
  ["完蛋了"]=>
  int(1)
  ["我才"]=>
  int(1)
  ["骂"]=>
  int(1)
  ["不发"]=>
  int(1)
  ["呵呵"]=>
  int(1)
  ["他"]=>
  int(1)
  ["去骂"]=>
  int(1)
  ["发条"]=>
  int(1)
  ["好想"]=>
  int(1)
  ["回家"]=>
  int(1)
  ["各自"]=>
  int(1)
  ["完蛋"]=>
  int(1)
}
string(482) "|七/s|./s|各自/r313|回家/n178|漂亮/a349|小妞/n5|奇怪/v93|我/s|真的/d444|好想/s|发条/n5|信息/n13020|去骂/s|他/s|./s|周星星/s|(/s|周/s|星星/n82|)/s|呵呵/o78|./s|她/s|不发/x1|信息/n13020|骂/s|我才/x5|奇怪/v93|./s|漂亮/a349|小妞/n5|完蛋了/s|难道/d139|我/s|真的/d444|喜欢/v912|那个/r421|无赖/n18|了/s|周星星/s|(/s|周/s|星星/n82|)/s|嘿嘿/o21|她/s|不喜欢/x1|我/s|这个/r4051|无赖/n18|那才/x1|叫/s|完蛋/v6|./s"
array(3) {
  [0]=>
  string(9) "周星星"
  [1]=>
  string(6) "无赖"
  [2]=>
  string(6) "星星"
}

```