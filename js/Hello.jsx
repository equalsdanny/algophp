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
            <div id="treeWrapper" style={{width: '50em', height: '20em'}}>
                <Tree orientation={'vertical'} data={tree}/>
            </div>
        );
    }

    static renderSession(session) {
        return session.map((tree, index) => (
           <div key={index}>
               {Hello.renderTree(tree)}
           </div>
        ));
    }

    render() {
        return <div>
            {this.state.loading && <h1>Loading</h1>}
            {!this.state.loading && Hello.renderSession(this.state.session)}
        </div>;
    }
}

ReactDOM.render(<Hello/>, document.getElementById('hello'));