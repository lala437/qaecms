<?php
/**
 * Created by PhpStorm.
 * User: hikki
 * Date: 2017/12/20
 * Time: 14:21
 */
namespace App\Librarys\Progress\src;

use App\Librarys\Progress\src\Loading;
use App\Librarys\Progress\src\roundStyle;

class progressType
{
    /**
     * @param $type
     * @return string
     */
    static function getProgress($type)
    {
        switch ($type){
            case Loading::LOAD_TYPE_STRAIGHT:
                return self::straightProgress();
                break;
            case Loading::LOAD_TYPE_ROUND:
                require_once "roundStyle.php";
                return self::roundProgress();
                break;
            case Loading::LOAD_TYPE_STRIPED;
                require_once "stripedStyle.php";
                return self::stripedProgress();
                break;
        }
    }

    /**
     * @param $proportion
     * @return string
     */
    static function getCurrentProgress($proportion)
    {
        return <<<EOF
<script>
    progress("{$proportion}")
</script>
EOF;
    }

    static function straightProgress()
    {
        $loading = Loading::$loading;
        $progress_long = Loading::$progress_long;
        return <<<EOF
<body style="background-color: #f5f7f9;color: #6c6c6c;">
<p id="pro" style="margin-top: 65px;text-align: center;"></P>
<div class="container" style="left: 50%; position: absolute;top: 100px;transform: translate(-50%, -50%);width:{$progress_long}px;">
	<div class="progress" style=" background-color: #e5e9eb;height: 0.25em;position: relative;width:{$progress_long}px;">
		<div id="{$loading}" class="progress-bar" style="
  background-image: linear-gradient(to right, #4cd964, #5ac8fa, #007aff, #34aadc, #5856d6, #ff2d55);
  background-size: 24em 0.25em;
  height: 100%;
  position: relative;
  width:0px;">
			<div class="progress-shadow" style="background-image: linear-gradient(to bottom, #eaecee, transparent);
  height: 4em;
  position: absolute;
  top: 100%;
  transform: skew(45deg);
  transform-origin: 0 0;
  width: 100%;"></div>
		</div>
	</div>
</div>
</body>
<script>
function progress(proportion){
   var total_long = {$progress_long};
   var dom = document.getElementById("{$loading}");
   var current_width = parseInt(dom.style.width);
   var progress = total_long * proportion;
   dom.style.width= progress + 'px';
   var bfb = Math.floor(proportion * 100);
   document.getElementById("pro").innerHTML = parseInt(bfb)==100?"全部完成":bfb+ '%';
}
</script>
EOF;
    }

    static function roundProgress()
    {
        $progress = Loading::$loading;
        $style = roundStyle::ini();
        return <<<EOF
 {$style}
<body style="background-color: #f5f7f9;color: #6c6c6c;height: auto">
<div class="progress-radial" id="{$progress}">
<b></b>
</div>
</body>
<script>
function progress(proportion){
      var ratio = Math.ceil(proportion * 100);
      var dom = document.getElementById("{$progress}");

      dom.className = "progress-radial progress-"+ratio
}
</script>
EOF;
    }

    static function stripedProgress()
    {
        $loading = Loading::$loading;
        $progress_type = Loading::$progress_striped_type;
        if(!$progress_type){
            $class = " navy ruler";
        }else{
            $class = $progress_type;
        }

        $style = stripedStyle::ini();
        return <<<EOF
 {$style}
  <body style="background-color: #f5f7f9;color: #D8DBE4;height: auto">
				<div class="flexy-column">
					<div class="progress-factor flexy-item">
						<div class="progress-bar">
							<div class="bar has-rotation has-colors{$class}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="{$loading}">
								<div class="tooltip white"></div>
								<div class="bar-face face-position roof percentage"></div>
								<div class="bar-face face-position back percentage"></div>
								<div class="bar-face face-position floor percentage volume-lights"></div>
								<div class="bar-face face-position left"></div>
								<div class="bar-face face-position right"></div>
								<div class="bar-face face-position front percentage volume-lights shine"></div>
							</div>
						</div>
					</div>
				</div>
</body>
<script>
function progress(proportion){
      var ratio = Math.ceil(proportion * 100);
      var dom = document.getElementById("{$loading}");

      dom.setAttribute('aria-valuenow',ratio);
}
</script>
EOF;
    }

}
