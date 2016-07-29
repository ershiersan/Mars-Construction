<?php
namespace app\components;
/**
 * Class Plugins_socialtouch_page
 * @note 网上找的分页类
 * @author zhangyufei <zhangyufei@social-touch.com>
 */
class Page {
    private $total;      //总记录
    private $pagesize;    //每页显示多少条
    private $limit;          //limit
    private $page;           //当前页码
    private $pagenum;      //总页码
    private $url;           //地址
    private $bothnum;      //两边保持数字分页的量

    //构造方法初始化
    public function __construct($_total, $_pagesize) {
        $this->total = $_total ? $_total : 1;
        $this->pagesize = $_pagesize;
        $this->pagenum = ceil($this->total / $this->pagesize);
        $this->page = $this->setPage();
        $this->limit = "LIMIT ".($this->page-1)*$this->pagesize.",$this->pagesize";
        $this->url = $this->setUrl();
        $this->bothnum = 2;
    }

    //拦截器
    private function __get($_key) {
        return $this->$_key;
    }

    //获取当前页码
    private function setPage() {
        if (!empty($_GET['page'])) {
            if ($_GET['page'] > 0) {
                if ($_GET['page'] > $this->pagenum) {
                    return $this->pagenum;
                } else {
                    return $_GET['page'];
                }
            } else {
                return 1;
            }
        } else {
            return 1;
        }
    }

    //创建url 取代setUrl方法
    private function createUrl($page) {
        $url = $_SERVER["REQUEST_URI"];
        $urlArr = parse_url($url);
        $query = http_build_query(array('page'=>$page));
        $urlTmp = $url;
        $url = str_replace('page='.$_GET['page'], 'page='.$page, $url);
        if($urlTmp != $url) {
            return $url;
        }
//        $url = str_replace('&page='.$_GET['page'], '', $url);
        if(isset($urlArr['query'])) {
            $url = str_replace($urlArr['query'], $urlArr['query'] .'&'. $query, $url);
        } else if(isset($urlArr['fragment'])) {
            $url = str_replace('#' . $urlArr['fragment'], '?'. $query . '#' . $urlArr['fragment'], $url);
        } else {
            $url .= '?' . $query;
        }
        return $url;
    }

    //获取地址
    private function setUrl() {
        $_url = $_SERVER["REQUEST_URI"];
        $_par = parse_url($_url);
        if (isset($_par['query'])) {
            parse_str($_par['query'],$_query);
            unset($_query['page']);
            $_url = $_par['path'].'?'.http_build_query($_query);
        } else {

        }
        return $_url;
    }     //数字目录
    private function pageList() {
        $_pagelist = '';
        for ($i=$this->bothnum;$i>=1;$i--) {
            $_page = $this->page-$i;
            if ($_page < 1) continue;
            $_pagelist .= ' <li><a href="'.$this->createUrl($_page).'">'.$_page.'</a></li> ';
        }
        $_pagelist .= ' <li><a href="javascript:;" class="on">'.$this->page.'</a></li> ';
        for ($i=1;$i<=$this->bothnum;$i++) {
            $_page = $this->page+$i;
            if ($_page > $this->pagenum) break;
            $_pagelist .= ' <li><a href="'.$this->createUrl($_page).'">'.$_page.'</a></li> ';
        }
        return $_pagelist;
    }

    //首页
    private function first() {
        if ($this->page > $this->bothnum+1) {
            return ' <a href="'.$this->createUrl(1).'">1</a> ...';
        }
    }

    //上一页
    private function prev() {
        if ($this->page == 1) {
            return '<li><span class="disabled">&lt;&lt;</span></li>';
        }
        return ' <li><a href="'.$this->createUrl($this->page-1).'">&lt;&lt;</a></li> ';
    }

    //下一页
    private function next() {
        if ($this->page == $this->pagenum) {
            return '<li><span class="disabled">&gt;&gt;</span></li>';
        }
        return ' <li><a href="'.$this->createUrl($this->page+1).'">&gt;&gt;</a></li> ';
    }

    //尾页
    private function last() {
        if ($this->pagenum - $this->page > $this->bothnum) {
            return ' ...<a href="'.$this->createUrl($this->pagenum).'">'.$this->pagenum.'</a> ';
        }
    }

    //数量
    private function count() {
        return '<a href="javascript:;">共' . $this->pagenum . '页</a>';
    }

    //跳页
    private function redirect() {
        return '<span>到第<input class="m-page" data-pagenum="' . $this->pagenum . '" type="text" value="' . $this->page . '">页</span><em class="c_x_gray_25">跳 转</em>';
    }

    //分页信息
    public function showpage() {
        $_page = '';
        $_page .= $this->prev();
//        $_page .= $this->first();
        $_page .= $this->pageList();
        $_page .= $this->next();
//        $_page .= $this->count();
//        $_page .= $this->redirect();
        return $_page;
    }
}
?>  