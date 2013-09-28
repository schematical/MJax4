(function(){
    window.MJax = MJax = {
        ControlDefinitions:{},
        Controls:{},
        Init:function(objFormData){
            MJax.FormData = objFormData;

            MJax.InitRequire();


            //MJax.Run();

        },
        LoadTemplate:function(){
            /*require(
                ['text!' + MJax.FormData.head.template],
                function(html) {
                    MJax.FormData.head.template_html = html;
                }
            );*/
        },
        InitRequire:function(){
            require.config(
                MJax.FormData.head.require
            );
            requirejs(
                ['jquery','jquery_mobile', 'mustache'],//, 'require_text'],
                function($, jMobile, Mustache) {
                    window.Mustache = Mustache;

                    MJax.InitControlDefinitions();
                }
            );
        },
        InitControlDefinitions:function(){
            requirejs(
                MJax.FormData.head.controls,
                function() {
                    MJax.Run();
                }
            );
        },
        Run:function(){
            for(var strId in MJax.FormData.body){
                var objControl = MJax.FormData.body[strId];
                var funCtl = MJax.ControlDefinitions[objControl.Type];
                MJax.Controls[objControl.ControlId] = new funCtl(objControl);

            }
            MJax.Render();
        },
        Render:function(){
            MJax.LoadTemplate();
            var strBody = window.Mustache.render(
                MJax.FormData.head.template_html,
                this
            );
            $('body').html(
                strBody
            );
        }

    };
})(window);
