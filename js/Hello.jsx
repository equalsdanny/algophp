import React from 'react';
import ReactDOM from 'react-dom';
import Tree from 'react-d3-tree';

class Hello extends React.Component {
    constructor() {
        super();
        this.state = {loading: true};

        this.load();
    }

    async load() {
        const session = await (await fetch('http://localhost:8000')).json();
        this.setState({loading: false, session});
    }

    static renderTree(tree) {
        return (
            <div id="treeWrapper" style={{border: '1px solid black', margin: '0.3em', width: '40em', height: '40em'}}>
                {tree.length > 0 && <Tree orientation={'vertical'} data={tree} translate={{x: 350, y: 20}}
                                          pathFunc='straight' zoom={0.8} zoomable={false} />}
                {tree.length === 0 && <div>Empty tree</div>}
            </div>
        );
    }

    static renderSession(session) {
        const renderTree = (tree, index) => <div key={index}>{Hello.renderTree(tree)}</div>;
        return (
            <div style={{display: 'flex', flexFlow: 'row wrap'}}>
                {session.map(renderTree)}
            </div>
        );
    }

    render() {
        return <div>
            {this.state.loading && <h1>Loading</h1>}
            {!this.state.loading && Hello.renderSession(this.state.session)}
        </div>;
    }
}

ReactDOM.render(<Hello/>, document.getElementById('hello'));