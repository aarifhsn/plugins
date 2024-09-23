//  add a demo text

import { Outlet, Link, Form } from "react-router-dom";
import { Button } from "@wordpress/components";
import apiFetch from "@wordpress/api-fetch";

export default function PostView() {
  const onSubmit = (event) => {
    event.preventDefault();

    const formData = new FormData(event.target);

    apiFetch({
      path: wpt_data.ajaxurl,
      method: "POST",
      data: {
        title: formData.get("title"),
        content: formData.get("content"),
      },
    });
  };
  return (
    <div>
      <form onSubmit={onSubmit}>
        <table>
          <thead>
            <tr>
              <th>Form Report</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <label htmlFor="title">Title</label>
              </td>
              <td>
                <input
                  type="text"
                  id="title"
                  name="title"
                  placeholder="Title"
                />
              </td>
            </tr>
            <tr>
              <td>
                <label htmlFor="content">Content</label>
              </td>
              <td>
                <textarea id="content" name="content"></textarea>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <Button type="submit">Submit</Button>
              </td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  );
}
