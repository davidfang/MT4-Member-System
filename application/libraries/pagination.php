<?php

/**
 * 分页类，表现层基于bootstrap
 *
 * @author Eddy
 * */
class Pagination {
	private $pageSize; // 每页显示记录数
	private $totalCount; // 总记录数
	private $pageUrl; // 页面链接
	private $pageNavNum; // 导航条条目数
	public $pageNum; // 总页数
	public $curPageNum; // 当前页面
	public $showGoto; // 是否显示跳转连接
	public $position; // 分页导航位置 right center left
	public $showTotal; // 是否显示总页数
	                   
	// 构造函数
	public function __construct($params) {
		
		$this->totalCount = $params['totalCount'];
		$this->pageSize = $params['pageSize'];
		$this->pageUrl = $params['pageUrl'];
		$this->pageNavNum = $params['pageNavNum'] > 3 ? $params['pageNavNum'] : 3;
		$this->pageNum = ceil ( $params['totalCount'] / $params['pageSize'] );
		$this->position = $params['position'];
		$this->showGoto = $params['showGoto'];
		$this->showTotal = $params['showTotal'];
	}
	
	// 获取分页导航链接数组
	private function getNavUrlArr() {
		$navUrl = array ();
		if ($this->pageNum > $this->pageNavNum) {
			if ($this->pageNum - $this->curPageNum < $this->pageNavNum) {
				$start = $this->pageNum - $this->pageNavNum + 1;
			} else {
				$start = $this->curPageNum;
			}
			
			for($i = $start; $i < min ( $this->pageNavNum + $this->curPageNum, $this->pageNum + 1 ); $i ++) {
				if ($this->curPageNum == $i) {
					$navUrl [] = '<li class="active"><a href="' . $this->pageUrl . $i . '">' . $i . '</a></li>';
				} else {
					$navUrl [] = '<li><a href="' . $this->pageUrl . $i . '">' . $i . '</a></li>';
				}
			}
		} else {
			for($i = 0; $i < $this->pageNum; $i ++) {
				if ($this->curPageNum == $i + 1) {
					$navUrl [] = '<li class="active"><a href="' . $this->pageUrl . ($i + 1) . '">' . ($i + 1) . '</a></li>';
				} else {
					$navUrl [] = '<li><a href="' . $this->pageUrl . ($i + 1) . '">' . ($i + 1) . '</a></li>';
				}
			}
		}
		
		return $navUrl;
	}
	
	// 生成分页导航条
	public function generatePageNav() {
		$navStr = '';
		$midStr = '';
		if ($this->position == 'right') {
			$navStr .= '<div class="pagination pagination-right"><ul>';
		} else if ($this->position == 'center') {
			$navStr .= '<div class="pagination pagination-centered"><ul>';
		} else {
			$navStr .= '<div class="pagination"><ul>';
		}
		if ($this->totalCount <= 0) {
			return null;
		}
		$navUrl = $this->getNavUrlArr ();
		foreach ( $navUrl as $v ) {
			$midStr .= $v;
		}
		if ($this->curPageNum == 1) {
			if ($this->pageNum > 1) {
				$navStr .= $midStr . '<li><a href="' . $this->pageUrl . ($this->curPageNum + 1) . '">下一页&gt;</a></li>' . '<li><a href="' . $this->pageUrl . $this->pageNum . '">末页</a></li>';
			} else {
				$navStr .= $midStr;
			}
		} else if ($this->curPageNum == $this->pageNum) {
			$navStr .= '<li><a href="' . $this->pageUrl . '1">首页</a></li>' . '<li><a href="' . $this->pageUrl . ($this->curPageNum - 1) . '">&lt;上一页</a></li>' . $midStr;
		} else {
			$navStr .= '<li><a href="' . $this->pageUrl . '1">首页</a></li>' . '<li><a href="' . $this->pageUrl . ($this->curPageNum - 1) . '">&lt;上一页</a></li>' . $midStr . '<li><a href="' . $this->pageUrl . ($this->curPageNum + 1) . '">下一页&gt;</a></li>' . '<li><a href="' . $this->pageUrl . $this->pageNum . '">末页</a></li>';
		}
		
		if ($this->showTotal) {
			$navStr .= "<li><a>当前第 $this->curPageNum 页|共 $this->pageNum 页</a></li>";
		}
		
		if ($this->showGoto) {
			$navStr .= '<li><a>跳转至：<select onchange="goto(this)">';
			for($i = 1; $i < $this->pageNum + 1; $i ++) {
				if ($i == $this->curPageNum) {
					$navStr .= "<option selected='selected' value='" . $this->pageUrl . "$i'>第 $i 页</option>";
				} else {
					$navStr .= "<option value='" . $this->pageUrl . "$i'>第 $i 页</option>";
				}
			}
		}
		$navStr .= '</select></a></li></ul></div>';
		
		$script = '<script type="text/javascript">
	function goto(th){
		window.location.href = th.value;
	}
</script>';
		$navStr .= $script;
		return $navStr;
	}
}

// END class pagination
?>