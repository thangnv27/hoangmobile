<style type="text/css">
    .wrap-block{padding: 10px; border: 2px dashed #2f8dc1;}
    #dhtmlgoodies_dragDropContainer{	/* Main container for this script */
        -moz-user-select:none;
        overflow: hidden;
    }
    #dhtmlgoodies_dragDropContainer ul{	/* General rules for all <ul> */
        margin-top:0px;
        margin-left:0px;
        margin-bottom:0px;
    }

    #dhtmlgoodies_dragDropContainer li,#dragContent li,li#indicateDestination{	/* Movable items, i.e. <LI> */
        list-style-type:none;
        background-color:#EEE;
        border:1px solid #bebebe;
        padding:5px;
        float: left; margin-right: 10px;
        cursor: move;
    }

    li#indicateDestination{	/* Box indicating where content will be dropped - i.e. the one you use if you don't use arrow */
        border:1px solid #317082;
        background-color:#FFF;
    }

    /* LEFT COLUMN CSS */
    div#dhtmlgoodies_listOfItems{	/* Left column "Available students" */
    }

    div#dhtmlgoodies_listOfItems div{
        border:1px solid #999; 
    }
    div#dhtmlgoodies_listOfItems div ul{	/* Left column <ul> */
        overflow: hidden; padding: 10px;
    }
    #dhtmlgoodies_listOfItems div p{	/* Heading above left column */
        margin:0px;
        font-weight:bold;
        padding-left:12px;
        background-color:#317082;
        color:#FFF;
        margin-bottom:5px;
    }
    /* END LEFT COLUMN CSS */

    #dhtmlgoodies_dragDropContainer .mouseover{	/* Mouse over effect DIV box in right column */
        background-color:#E2EBED;
        border:2px solid #317082;
        cursor: move;
    }

    /* Start main container CSS */

    div#dhtmlgoodies_mainContainer{	/* Right column DIV */
    }
    #dhtmlgoodies_mainContainer div{	/* Parent <div> of small boxes */
        padding: 10px; 
        border: 2px dashed #2f8dc1;
        overflow: hidden;
    }
    #dhtmlgoodies_mainContainer div ul{
        border:0px;
        overflow:hidden;
    }

    #dragContent{	/* Drag container */
        position:absolute;
        width:150px;
        height:20px;
        display:none;
        margin:0px;
        padding:0px;
        z-index:2000;
    }

    #dragDropIndicator{	/* DIV for the small arrow */
        position:absolute;
        width:7px;
        height:10px;
        display:none;
        z-index:1000;
        margin:0px;
        padding:0px;
    }
</style>
<style type="text/css" media="print">
    div#dhtmlgoodies_listOfItems{
        display:none;
    }
    #dhtmlgoodies_dragDropContainer{
        border:0px;
        width:100%;
    }
</style>
<script type="text/javascript">
    /* VARIABLES YOU COULD MODIFY */
    var boxSizeArray = [10,6,6,6,6,6];	// Array indicating how many items there is rooom for in the right column ULs


    var verticalSpaceBetweenListItems = 3;	// Pixels space between one <li> and next
    // Same value or higher as margin bottom in CSS for #dhtmlgoodies_dragDropContainer ul li,#dragContent li


    var indicateDestionationByUseOfArrow = true;	// Display arrow to indicate where object will be dropped(false = use rectangle)

    var cloneSourceItems = false;	// Items picked from main container will be cloned(i.e. "copy" instead of "cut").
    var cloneAllowDuplicates = false;	// Allow multiple instances of an item inside a small box(example: drag Student 1 to team A twice

    /* END VARIABLES YOU COULD MODIFY */

    var dragDropTopContainer = false;
    var dragTimer = -1;
    var dragContentObj = false;
    var contentToBeDragged = false;	// Reference to dragged <li>
    var contentToBeDragged_src = false;	// Reference to parent of <li> before drag started
    var contentToBeDragged_next = false; 	// Reference to next sibling of <li> to be dragged
    var destinationObj = false;	// Reference to <UL> or <LI> where element is dropped.
    var dragDropIndicator = false;	// Reference to small arrow indicating where items will be dropped
    var ulPositionArray = new Array();
    var mouseoverObj = false;	// Reference to highlighted DIV

    var MSIE = navigator.userAgent.indexOf('MSIE')>=0?true:false;
    var navigatorVersion = navigator.appVersion.replace(/.*?MSIE (\d\.\d).*/g,'$1')/1;

    var arrow_offsetX = -5;	// Offset X - position of small arrow
    var arrow_offsetY = 0;	// Offset Y - position of small arrow

    if(!MSIE || navigatorVersion > 6){
        arrow_offsetX = -6;	// Firefox - offset X small arrow
        arrow_offsetY = -13; // Firefox - offset Y small arrow
    }

    var indicateDestinationBox = false;
    function getTopPos(inputObj)
    {
        var returnValue = inputObj.offsetTop;
        while((inputObj = inputObj.offsetParent) != null){
            if(inputObj.tagName!='HTML')returnValue += inputObj.offsetTop;
        }
        return returnValue;
    }

    function getLeftPos(inputObj)
    {
        var returnValue = inputObj.offsetLeft;
        while((inputObj = inputObj.offsetParent) != null){
            if(inputObj.tagName!='HTML')returnValue += inputObj.offsetLeft;
        }
        return returnValue;
    }

    function cancelEvent()
    {
        return false;
    }
    function initDrag(e)	// Mouse button is pressed down on a LI
    {
        if(document.all)e = event;
        var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
        var sl = Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);

        dragTimer = 0;
        dragContentObj.style.left = e.clientX + sl + 'px';
        dragContentObj.style.top = e.clientY + st + 'px';
        contentToBeDragged = this;
        contentToBeDragged_src = this.parentNode;
        contentToBeDragged_next = false;
        if(this.nextSibling){
            contentToBeDragged_next = this.nextSibling;
            if(!this.tagName && contentToBeDragged_next.nextSibling)contentToBeDragged_next = contentToBeDragged_next.nextSibling;
        }
        timerDrag();
        return false;
    }

    function timerDrag()
    {
        if(dragTimer>=0 && dragTimer<10){
            dragTimer++;
            setTimeout('timerDrag()',10);
            return;
        }
        if(dragTimer==10){

            if(cloneSourceItems && contentToBeDragged.parentNode.id=='allItems'){
                newItem = contentToBeDragged.cloneNode(true);
                newItem.onmousedown = contentToBeDragged.onmousedown;
                contentToBeDragged = newItem;
            }
            dragContentObj.style.display='block';
            dragContentObj.appendChild(contentToBeDragged);
        }
    }

    function moveDragContent(e)
    {
        if(dragTimer<10){
            if(contentToBeDragged){
                if(contentToBeDragged_next){
                    contentToBeDragged_src.insertBefore(contentToBeDragged,contentToBeDragged_next);
                }else{
                    contentToBeDragged_src.appendChild(contentToBeDragged);
                }
            }
            return;
        }
        if(document.all)e = event;
        var st = Math.max(document.body.scrollTop,document.documentElement.scrollTop);
        var sl = Math.max(document.body.scrollLeft,document.documentElement.scrollLeft);
        var adminmenuWidth = document.getElementById('adminmenuwrap').offsetWidth;
        
        dragContentObj.style.left = e.clientX + sl - adminmenuWidth + 'px';
        dragContentObj.style.top = e.clientY + st - 40 + 'px';

        if(mouseoverObj)mouseoverObj.className='';
        destinationObj = false;
        dragDropIndicator.style.display='none';
        if(indicateDestinationBox)indicateDestinationBox.style.display='none';
        var x = e.clientX + sl;
        var y = e.clientY + st;
        var width = dragContentObj.offsetWidth;
        var height = dragContentObj.offsetHeight;

        var tmpOffsetX = arrow_offsetX;
        var tmpOffsetY = arrow_offsetY;

        for(var no=0;no<ulPositionArray.length;no++){
            var ul_leftPos = ulPositionArray[no]['left'];
            var ul_topPos = ulPositionArray[no]['top'];
            var ul_height = ulPositionArray[no]['height'];
            var ul_width = ulPositionArray[no]['width'];

            if((x+width) > ul_leftPos && x<(ul_leftPos + ul_width) && (y+height)> ul_topPos && y<(ul_topPos + ul_height)){
                var noExisting = ulPositionArray[no]['obj'].getElementsByTagName('LI').length;
                if(indicateDestinationBox && indicateDestinationBox.parentNode==ulPositionArray[no]['obj'])noExisting--;
                if(noExisting<boxSizeArray[no-1] || no==0){
                    //dragDropIndicator.style.left = ul_leftPos + tmpOffsetX + 'px';
                    var subLi = ulPositionArray[no]['obj'].getElementsByTagName('LI');

                    var clonedItemAllreadyAdded = false;
                    if(cloneSourceItems && !cloneAllowDuplicates){
                        for(var liIndex=0;liIndex<subLi.length;liIndex++){
                            if(contentToBeDragged.id == subLi[liIndex].id)clonedItemAllreadyAdded = true;
                        }
                        if(clonedItemAllreadyAdded)continue;
                    }

                    for(var liIndex=0;liIndex<subLi.length;liIndex++){
                        var tmpTop = getTopPos(subLi[liIndex]);
                        if(!indicateDestionationByUseOfArrow){
                            if(y<tmpTop){
                                destinationObj = subLi[liIndex];
                                indicateDestinationBox.style.display='block';
                                subLi[liIndex].parentNode.insertBefore(indicateDestinationBox,subLi[liIndex]);
                                break;
                            }
                        }else{
                            if(y<tmpTop){
                                destinationObj = subLi[liIndex];
                                dragDropIndicator.style.top = tmpTop + tmpOffsetY - Math.round(dragDropIndicator.clientHeight/2) + 'px';
                                dragDropIndicator.style.display='block';
                                break;
                            }
                        }
                    }

                    if(!indicateDestionationByUseOfArrow){
                        if(indicateDestinationBox.style.display=='none'){
                            indicateDestinationBox.style.display='block';
                            ulPositionArray[no]['obj'].appendChild(indicateDestinationBox);
                        }

                    }else{
                        if(subLi.length>0 && dragDropIndicator.style.display=='none'){
                            //dragDropIndicator.style.top = getTopPos(subLi[subLi.length-1]) + subLi[subLi.length-1].offsetHeight + tmpOffsetY + 'px';
                            dragDropIndicator.style.top = y + tmpOffsetY + 'px';
                            dragDropIndicator.style.display='block';
                        }
                        if(subLi.length==0){
                            dragDropIndicator.style.top = ul_topPos + arrow_offsetY + 'px'
                            dragDropIndicator.style.display='block';
                        }
                    }

                    if(!destinationObj)destinationObj = ulPositionArray[no]['obj'];
                    mouseoverObj = ulPositionArray[no]['obj'].parentNode;
                    mouseoverObj.className='mouseover';
                    return;
                }
            }
        }
    }

    /* End dragging
Put <LI> into a destination or back to where it came from.
     */
    function dragDropEnd(e)
    {
        if(dragTimer==-1)return;
        if(dragTimer<10){
            dragTimer = -1;
            return;
        }
        dragTimer = -1;
        if(document.all)e = event;


        if(cloneSourceItems && (!destinationObj || (destinationObj && (destinationObj.id=='allItems' || destinationObj.parentNode.id=='allItems')))){
            contentToBeDragged.parentNode.removeChild(contentToBeDragged);
        }else{

            if(destinationObj){
                if(destinationObj.tagName=='UL'){
                    destinationObj.appendChild(contentToBeDragged);
                }else{
                    destinationObj.parentNode.insertBefore(contentToBeDragged,destinationObj);
                }
                mouseoverObj.className='';
                destinationObj = false;
                dragDropIndicator.style.display='none';
                if(indicateDestinationBox){
                    indicateDestinationBox.style.display='none';
                    document.body.appendChild(indicateDestinationBox);
                }
                contentToBeDragged = false;
                
                saveDragDropNodes();
                
                return;
            }
            if(contentToBeDragged_next){
                contentToBeDragged_src.insertBefore(contentToBeDragged,contentToBeDragged_next);
            }else{
                contentToBeDragged_src.appendChild(contentToBeDragged);
            }
        }
        contentToBeDragged = false;
        dragDropIndicator.style.display='none';
        if(indicateDestinationBox){
            indicateDestinationBox.style.display='none';
            document.body.appendChild(indicateDestinationBox);

        }
        mouseoverObj = false;
    }

    /**
     * Preparing data to be saved
     */
    function saveDragDropNodes()
    {
        var saveString = "{";
        var mainContainer = document.getElementById('dhtmlgoodies_mainContainer');
        var uls = mainContainer.getElementsByTagName('UL');
        for(var no=0;no<uls.length;no++){	// LOoping through all <ul>
            var lis = uls[no].getElementsByTagName('LI');
            saveString += '"' + uls[no].id + '": [';
            for(var no2=0;no2<lis.length;no2++){
                if(no2 == lis.length -1){
                    saveString += '{"term_id":' + lis[no2].id + '}';
                }else{
                    saveString += '{"term_id":' + lis[no2].id + '},';
                }
            }
            if(no == uls.length -1){
                saveString += ']';
            }else{
                saveString += '],';
            }
            
        }
        saveString += "}";

        document.getElementById('saveContent').value = saveString;

    }

    function initDragDropScript()
    {
        dragContentObj = document.getElementById('dragContent');
        dragDropIndicator = document.getElementById('dragDropIndicator');
        dragDropTopContainer = document.getElementById('dhtmlgoodies_dragDropContainer');
        document.documentElement.onselectstart = cancelEvent;;
        var listItems = dragDropTopContainer.getElementsByTagName('LI');	// Get array containing all <LI>
        var itemHeight = false;
        for(var no=0;no<listItems.length;no++){
            listItems[no].onmousedown = initDrag;
            listItems[no].onselectstart = cancelEvent;
            if(!itemHeight)itemHeight = listItems[no].offsetHeight;
            if(MSIE && navigatorVersion/1<6){
                listItems[no].style.cursor='hand';
            }
        }

        var mainContainer = document.getElementById('dhtmlgoodies_mainContainer');
        var uls = mainContainer.getElementsByTagName('UL');
        itemHeight = itemHeight + verticalSpaceBetweenListItems;
        for(var no=0;no<uls.length;no++){
            //uls[no].style.height = itemHeight * boxSizeArray[no]  + 'px';
        }

        var leftContainer = document.getElementById('dhtmlgoodies_listOfItems');
        var itemBox = leftContainer.getElementsByTagName('UL')[0];

        document.documentElement.onmousemove = moveDragContent;	// Mouse move event - moving draggable div
        document.documentElement.onmouseup = dragDropEnd;	// Mouse move event - moving draggable div

        var ulArray = dragDropTopContainer.getElementsByTagName('UL');
        for(var no=0;no<ulArray.length;no++){
            ulPositionArray[no] = new Array();
            ulPositionArray[no]['left'] = getLeftPos(ulArray[no]);
            ulPositionArray[no]['top'] = getTopPos(ulArray[no]);
            ulPositionArray[no]['width'] = ulArray[no].offsetWidth;
            ulPositionArray[no]['height'] = ulArray[no].clientHeight;
            ulPositionArray[no]['obj'] = ulArray[no];
        }

        if(!indicateDestionationByUseOfArrow){
            indicateDestinationBox = document.createElement('LI');
            indicateDestinationBox.id = 'indicateDestination';
            indicateDestinationBox.style.display='none';
            document.body.appendChild(indicateDestinationBox);
        }
    }

    window.onload = initDragDropScript;
</script>
<div class="wrap">
    <div class="opwrap" style="margin-top: 10px;" >
        <div class="icon32" id="icon-options-general"></div>
        <h2 class="wraphead"><?php echo $themename; ?> home options</h2>
        <?php
        if (isset($_REQUEST['saved']))
            echo '<div id="message" class="updated fade"><p><strong>' . $themename . ' settings saved.</strong></p></div>';
            $taxonomy = 'product_category';
            $categories = get_categories(array(
                'type' => 'product',
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
            ));
        ?>
        <form method="post">
            <table class="form-table" cellpadding="3" cellspacing="0">
                <tr>
                    <td>
                        <div id="dhtmlgoodies_dragDropContainer">
                            <div id="dhtmlgoodies_listOfItems">
                                <div>
                                    <p>Available categories</p>
                                    <ul id="allItems">
                                        <?php
                                        foreach ($categories as $category) {
                                            echo "<li id=\"{$category->term_id}\">{$category->name}</li>";
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div id="dhtmlgoodies_mainContainer">
                                <!-- ONE <UL> for each "room" -->
                                <table class="form-table" cellpadding="3" cellspacing="0">
                                    <tr>
                                        <td colspan="2">
                                            <div>
                                                <ul id="box1">
                                                    <?php
                                                    $boxArr = json_decode(get_option('cat_box1'));
                                                    if(count($boxArr) > 0){
                                                        foreach ($boxArr as $catID) {
                                                            $category = get_term($catID, $taxonomy);
                                                            echo "<li id=\"{$category->term_id}\">{$category->name}</li>";
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <ul id="dragContent"></ul>
                        <div id="dragDropIndicator"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/insert.gif"></div>
                        <input type="hidden" name="saveContent" id="saveContent" value="" />
                        <div class="description">Each block max items = 10</div>
                    </td>
                </tr>
            </table>
            <div class="submit" style="margin-left: 10px;">
                <input name="save" type="submit" value="Save changes" class="button button-large button-primary" />
                <input type="hidden" name="action" value="save" />
            </div>
        </form>
    </div>
</div>