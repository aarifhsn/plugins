import { Outlet, Link } from "react-router-dom";
import { Panel, PanelBody } from "@wordpress/components";
function App() {
  return (
    <Panel>
      <PanelBody title="Menu">
        <div className="body">
          <h1>Loaded from React</h1>
          <ul>
            <li>
              <Link to="/">Item 1</Link>
            </li>
            <li>
              <Link to="qr-code">Item 2</Link>
            </li>
          </ul>

          <Outlet />
        </div>
      </PanelBody>
    </Panel>
  );
}

export default App;
