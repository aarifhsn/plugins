import { createRoot } from "react-dom/client";

import { createHashRouter, RouterProvider } from "react-router-dom";

import PostView from "../components/PostView";
import QRCode from "../components/QRCode";

import "../css/main.scss";
import App from "../components/app";

const router = createHashRouter([
  {
    path: "/",
    element: <App />,
    children: [
      {
        path: "",
        element: <PostView />,
      },

      {
        path: "qr-code",
        element: <QRCode />,
      },
    ],
  },
]);

const rootElement = document.getElementById("react-app");
const root = createRoot(rootElement);

root.render(<RouterProvider router={router} />);
