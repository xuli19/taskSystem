<?php

    /**
     * php分页实现
     *
     * @param [int] $total
     * @param [int] $currentpage
     * @param [int] $pageSize
     * @param int $show
     * @return void
     */
    function pagination($total,$currentpage,$pageSize,$show =6){
        $paginationStr = '';
        
        //写死翻页路径，后续可以更改
        $url = (string)parse_url($_SERVER['REQUEST_URI'])['path'].'?page=';
        
        // 只有当总页数大于每页显示的条数才需要进行分页处理
        if ($total > $pageSize) {
            // 那么到底应该分好多页呢？
            $totalPages = ceil($total/$pageSize);

            // 当前页的容错处理
            $currentpage = $currentpage>$totalPages?$totalPages:$currentpage;
            $paginationStr .='<div class="Page pagination pagination-sm example">';
            $paginationStr .='<ul class="pagination" style="margin:0 auto">';

            $from = max(1, ($currentpage-intval($show/2)));
            $to = $from+$show-1;
            if ($to>$totalPages) {
                $to=$totalPages;
                $from=max(1, $to-$show+1);
            }

            
            if($currentpage>1){
                // 超过1页的时候就应该显示首页、上一页
                $paginationStr .='<li class="page-item"><a class="page-link" href='.$url.'1'.'>首页</a></li>';
                $paginationStr .="<li class='page-item'><a class='page-link' href='".$url.($currentpage-1)."'>上一页</a></li>";

            } 

            if ($from>1) {
                $paginationStr .= '<li class="page-item page-link" >...</li>';
            }

            for($i=$from;$i<=$to; $i++){
                if ($i != $currentpage ) {
                    $paginationStr .="<li class='page-item' ><a class='page-link' href='".$url.$i."'>{$i}</a></li>";
                } else {
                    $paginationStr .= "<li  class='page-item'><span class='page-link' style='background-color:#7fb4ff' >{$i}</span></li>";
                }
            }

            if($to<$totalPages) {
                $paginationStr .='<li class="page-item page-link">...</li>';
            }


            // 如果当前页小于总页数,那么就应该存在下一页、尾页
            if ($currentpage<$totalPages) {
                
                $paginationStr .="<li class='page-item'><a class='page-link' href='".$url.($currentpage+1)."'>下一页</a></li>";
                $paginationStr .="<li class='page-item'><a class='page-link' href=".$url.$totalPages.">尾页</a></li>";
            }
            $paginationStr .='</ul>';
            $paginationStr .='</div>';            
        }else{
            echo "errole!";
        }
        return $paginationStr;
    }
