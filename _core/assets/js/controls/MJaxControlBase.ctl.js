/*define( function () {*/
    var MJaxControlBase = function(objData){
        this.jEle = null;
        this.node = 'div';

        for(var strKey in objData){
            this[strKey] = objData[strKey];
        }

        return this;
    }
    MJaxControlBase.prototype.type = 'MJaxControlBase';
    MJaxControlBase.prototype.Render = function(strText, strRender){
        if(this.jEle == null){
            //Create a new Ele
            var strHtml = window.Mustache.render(MJax.FormData.head.controls_tpls[this.Type], this);
           /* require(
                ['text!' + this.template],
                function(module, html, css) {

                }
            );
            console.log(strHtml);*/
            return strHtml;
        }else{
            //update existing ele
        }
    }
MJax.ControlDefinitions['MJaxControlBase'] = MJaxControlBase;
/*
    return MJaxControlBase;
});
*/

