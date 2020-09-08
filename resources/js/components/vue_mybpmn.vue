<template>
    <div ref="container" class="vue-bpmn-diagram-container"></div>
</template>

<script>
    import BpmnJS from 'bpmn-js/dist/bpmn-navigated-viewer.production.min.js';

    export default {
        name: 'vue_mybpmn',
        props: {
            url: {
                type: String,
                required: true
            },
            process:{
                type:String,
                required: true
            },
        },
        data: function() {

            return {
                diagramXML: null,
                proces:this.process,
            };
        },
        mounted: function () {


            var container = this.$refs.container;

            var self = this;

            this.bpmnViewer = new BpmnJS({
                container: container
            });

            this.bpmnViewer.on('import.done', function(event) {

                var error = event.error;
                var warnings = event.warnings;

                if (error) {
                    self.$emit('error', error);
                } else {
                    self.$emit('shown', warnings);
                }

                self.bpmnViewer.get('canvas').zoom('fit-viewport');
                console.log("import was done");

                    var overlays = self.bpmnViewer.get('overlays');

                    var elementRegistry = self.bpmnViewer.get('elementRegistry');
                    console.log(elementRegistry);
                    var shape = elementRegistry.get(JSON.parse(self.proces).state+'');
                    console.log(shape);
                    var $overlayHtml =
                        $('<div class="highlight-overlay">')
                            .css({
                                width: shape.width,
                                height: shape.height
                            });

                    overlays.add(JSON.parse(self.proces).state+'', {
                        position: {
                            top: -0,
                            left: -0
                        },
                        html: $overlayHtml
                    });
                var EventNode = document.querySelector('[data-element-id='+JSON.parse(self.proces).state+']');
                console.log(JSON.parse(self.proces));
                EventNode.addEventListener('click', function(e) {
                    alert('Updated at : '+JSON.parse(self.proces).updated_at);
                });



                })


            if (this.url) {
                this.fetchDiagram(this.url);
            }



        },
        beforeDestroy: function() {
            this.bpmnViewer.destroy();
        },
        watch: {
            url: function(val) {
                this.$emit('loading');
                this.fetchDiagram(url);
            },
            diagramXML: function(val) {
                this.bpmnViewer.importXML(val);
            }
        },
        methods: {
            fetchDiagram: function(url) {

                var self = this;

                fetch(url)
                    .then(function(response) {
                        return response.text();
                    })
                    .then(function(text) {
                        self.diagramXML = text;
                    })
                    .catch(function(err) {
                        self.$emit('error', err);
                    });

            },

        },

    };
   // console.log(jQuery('g[data-element-id="Activity_10bp87e"], .djs-visual, .rect'))
</script>

<style>
    .vue-bpmn-diagram-container {
        height: 100%;
        width: 100%;
    }
    g[data-element-id="Activity_10bp87e"], .djs-visual, .rect  {

        fill: yellow !important;
    }
    .highlight-overlay {
        background-color: green; /* color elements as green */
        opacity: 0.4;
        pointer-events: none; /* no pointer events, allows clicking through onto the element */
    }

</style>
