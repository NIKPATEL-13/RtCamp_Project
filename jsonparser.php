<?php

			
			
			//------Sample Json Data Using ufdRecursive function.------";		
			
			//echo "Sample Json Data Using <b>udfRecursive</b> function.<br/><br/>";
			function udfRecursiveTraverse($arr)
			{	
				if(is_array($arr))
				{
					echo "<ul>";
					foreach($arr as $key=>$val)
					{
						if(is_array($val))
						{
							echo "<b><li style='color:red'>$key</li><br/></b>";
							if($key == "albums")
							{
								
							}
							udfRecursiveTraverse($val);
						}
						else
						{
							
							if(is_a($val, 'DateTime'))
							{
								$val = $GLOBALS['profile']['birthday']->format('j-F-Y');
							}
							echo "<li style='color:green'>$key : $val </li><br/>";
						}
					}
					echo "</ul>";
				}
				else
				{
					echo "$key : $val <br/>";
				}
			}
			
?>