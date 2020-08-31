import pizzaDiagram from './app/process/PullRequestProcess.bpmn';


// make sure you added bpmn-js to your your project
// dependencies via npm install --save bpmn-js
import BpmnViewer from '../node_modules/bpmn-js/index.js';

var viewer = new BpmnViewer({
    container: '#canvas'
});


viewer.importXML(pizzaDiagram).then(function(result) {

    const { warnings } = result;

    console.log('success !', warnings);

    viewer.get('canvas').zoom('fit-viewport');
}).catch(function(err) {

    const { warnings, message } = err;

    console.log('something went wrong:', warnings, message);
});
