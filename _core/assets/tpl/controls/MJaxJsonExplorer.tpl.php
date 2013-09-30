<div id='{{ControlId}}' class='MJaxJsonExplorer' data-role="collapsible" data-collapsed="false" data-theme="b" data-content-theme="d">
    <h4>Heading</h4>
    <ul data-role="listview">
        {{#RenderData}}
            <li>
                <a href="#" data-key='{{name}}'>
                   {{name}} - {{text}}
                </a>
            </li>
        {{/RenderData}}
    </ul>
</div>