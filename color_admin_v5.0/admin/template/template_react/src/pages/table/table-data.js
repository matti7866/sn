import React from 'react';
import { useTable, useSortBy, usePagination } from 'react-table'
import { Link } from 'react-router-dom';
import { Panel, PanelHeader, PanelBody } from './../../components/panel/panel.jsx';
import makeData from './make-data';
import Highlight from 'react-highlight';

const TableData = () => {
	const columns = React.useMemo(
    () => [
      {
        Header: 'Name',
        columns: [
          {
            Header: 'First Name',
            accessor: 'firstName',
            sortable: true
          },
          {
            Header: 'Last Name',
            accessor: 'lastName',
            sortable: true
          },
        ],
      },
      {
        Header: 'Info',
        columns: [
          {
            Header: 'Age',
            accessor: 'age',
            sortable: true
          },
          {
            Header: 'Visits',
            accessor: 'visits',
            sortable: true
          },
          {
            Header: 'Status',
            accessor: 'status',
            sortable: true
          },
          {
            Header: 'Profile Progress',
            accessor: 'progress',
            sortable: true
          },
        ],
      },
    ],
    []
  )

  const data = React.useMemo(() => makeData(200), [])
  
  const {
    getTableProps,
    getTableBodyProps,
    headerGroups,
    prepareRow,
    page, 
    
    canPreviousPage,
    canNextPage,
    pageOptions,
    pageCount,
    gotoPage,
    nextPage,
    previousPage,
    setPageSize,
    state: { pageIndex, pageSize },
  } = useTable({ columns, data, initialState: { pageIndex: 2 }, }, useSortBy, usePagination)

	 
	return (
		<div>
			<ol className="breadcrumb float-xl-end">
				<li className="breadcrumb-item"><Link to="/table/data">Home</Link></li>
				<li className="breadcrumb-item"><Link to="/table/data">Tables</Link></li>
				<li className="breadcrumb-item active">Data Tables</li>
			</ol>
			<h1 className="page-header">React Table <small>official documentation <a href="https://react-table.tanstack.com/docs/overview" target="_blank" rel="noopener noreferrer">here</a></small></h1>
			<Panel>
				<PanelHeader>
					React Table
				</PanelHeader>
			 	<PanelBody>
					<div className="d-flex align-items-center">
						<label className="form-label pe-2 mb-0">Page Length:</label>
						<div>
							<select
								className="form-select"
								value={pageSize}
								onChange={e => {
									setPageSize(Number(e.target.value))
								}}
							>
								{[10, 20, 30, 40, 50].map(pageSize => (
									<option key={pageSize} value={pageSize}>
										Show {pageSize}
									</option>
								))}
							</select>
						</div>
					</div>
				</PanelBody>
				<div className="table-responsive">
					<table className="table table-panel table-bordered mb-0" {...getTableProps()}>
						<thead>
							{headerGroups.map(headerGroup => (
								<tr {...headerGroup.getHeaderGroupProps()}>
									{headerGroup.headers.map(column => (
										<th className="w-150px" {...column.getHeaderProps(column.getSortByToggleProps())}>
											<div className="d-flex align-items-center" style={{minWidth: '150px'}}>
												<span>{column.render('Header')}</span>
												<span className="ms-auto">
													{column.sortable ?
														column.isSorted
															? column.isSortedDesc
																? <i className="fa fa-sort-down fa-fw fs-14px text-blue"></i>
																: <i className="fa fa-sort-up fa-fw fs-14px text-blue"></i>
															: <i className="fa fa-sort fa-fw fs-14px opacity-3"></i>
														: ''}
												</span>
											</div>
										</th>
									))}
								</tr>
							))}
					 </thead>
					 <tbody {...getTableBodyProps()}>
							{page.map(
								(row, i) => {
									prepareRow(row);
									return (
										<tr {...row.getRowProps()}>
											{row.cells.map(cell => {
												return (
													<td {...cell.getCellProps()}>{cell.render('Cell')}</td>
												)
											})}
										</tr>
									)}
							)}
					 </tbody>
				 </table>
			 </div>
				<PanelBody>
					<div className="d-flex align-items-center justify-content-center">
						<div className="me-1">Go to page:</div>
						<div className="w-50px mx-2 me-auto">
							<input className="form-control" type="number" defaultValue={pageIndex + 1}
									onChange={e => {
										const page = e.target.value ? Number(e.target.value) - 1 : 0
										gotoPage(page)
									}}
								/>
						</div>
						<ul className="pagination mb-0">
							<li className="page-item"><button className="page-link" onClick={() => gotoPage(0)} disabled={!canPreviousPage}><i className="fa fa-angle-double-left"></i></button></li>
							<li className="page-item"><button className="page-link" onClick={() => previousPage()} disabled={!canPreviousPage}><i className="fa fa-angle-left"></i></button></li>
							<li className="page-item d-flex align-items-center px-2">
								<div>Page <strong>{pageIndex + 1} of {pageOptions.length}</strong></div>
							</li>
							<li className="page-item"><button className="page-link" onClick={() => nextPage()} disabled={!canNextPage}><i className="fa fa-angle-right"></i></button></li>
							<li className="page-item"><button className="page-link" onClick={() => gotoPage(pageCount - 1)} disabled={!canNextPage}><i className="fa fa-angle-double-right"></i></button></li>
						</ul>
					</div>
				</PanelBody>
				<div className="hljs-wrapper">
					<Highlight className='typescript'>{
'import { useTable, useSortBy, usePagination } from \'react-table\';\n'+
'import Highlight from \'react-highlight\';\n'+
'\n'+
'const TableData = () => {\n'+
'  const columns = React.useMemo(\n'+
'    () => [\n'+
'      {\n'+
'        Header: \'Name\',\n'+
'        columns: [\n'+
'          {\n'+
'            Header: \'First Name\',\n'+
'            accessor: \'firstName\',\n'+
'            sortable: true\n'+
'          },\n'+
'          {\n'+
'            Header: \'Last Name\',\n'+
'            accessor: \'lastName\',\n'+
'            sortable: true\n'+
'          },\n'+
'        ],\n'+
'      }\n'+
'    ],\n'+
'    []\n'+
'  )\n'+
'\n'+
'  const data = React.useMemo(() => makeData(200), [])\n'+
'  \n'+
'  const {\n'+
'    getTableProps,\n'+
'    getTableBodyProps,\n'+
'    headerGroups,\n'+
'    prepareRow,\n'+
'    page, \n'+
'    canPreviousPage,\n'+
'    canNextPage,\n'+
'    pageOptions,\n'+
'    pageCount,\n'+
'    gotoPage,\n'+
'    nextPage,\n'+
'    previousPage,\n'+
'    setPageSize,\n'+
'    state: { pageIndex, pageSize },\n'+
'  } = useTable({ columns, data, initialState: { pageIndex: 2 }, }, useSortBy, usePagination)\n'+
'  \n'+
'  <PanelBody>\n'+
'    <div className="d-flex align-items-center">\n'+
'      <label className="form-label pe-2 mb-0">Page Length:</label>\n'+
'      <div>\n'+
'        <select className="form-select" value={pageSize} onChange={e => {setPageSize(Number(e.target.value))}}>\n'+
'          {[10, 20, 30, 40, 50].map(pageSize => (\n'+
'            <option key={pageSize} value={pageSize}>\n'+
'              Show {pageSize}\n'+
'            </option>\n'+
'          ))}\n'+
'        </select>\n'+
'      </div>\n'+
'    </div>\n'+
'  </PanelBody>\n'+
'  <div className="table-responsive">\n'+
'    <table className="table table-panel table-bordered mb-0" {...getTableProps()}>\n'+
'      <thead>\n'+
'        {headerGroups.map(headerGroup => (\n'+
'          <tr {...headerGroup.getHeaderGroupProps()}>\n'+
'            {headerGroup.headers.map(column => (\n'+
'              <th className="w-150px" {...column.getHeaderProps(column.getSortByToggleProps())}>\n'+
'                <div className="d-flex" style={{minWidth: \'150px\'}}>\n'+
'                  <span>{column.render(\'Header\')}</span>\n'+
'                  <span className="ms-auto">\n'+
'                    {column.sortable ?\n'+
'                      column.isSorted\n'+
'                        ? column.isSortedDesc\n'+
'                          ? <i className="fa fa-sort-down fa-fw fs-14px text-blue"></i>\n'+
'                          : <i className="fa fa-sort-up fa-fw fs-14px text-blue"></i>\n'+
'                        : <i className="fa fa-sort fa-fw fs-14px opacity-3"></i>\n'+
'                      : \'\'}\n'+
'                  </span>\n'+
'                </div>\n'+
'              </th>\n'+
'            ))}\n'+
'          </tr>\n'+
'        ))}\n'+
'     </thead>\n'+
'     <tbody {...getTableBodyProps()}>\n'+
'        {page.map(\n'+
'          (row, i) => {\n'+
'            prepareRow(row);\n'+
'            return (\n'+
'              <tr {...row.getRowProps()}>\n'+
'                {row.cells.map(cell => {\n'+
'                  return (\n'+
'                    <td {...cell.getCellProps()}>{cell.render(\'Cell\')}</td>\n'+
'                  )\n'+
'                })}\n'+
'              </tr>\n'+
'            )}\n'+
'        )}\n'+
'     </tbody>\n'+
'   </table>\n'+
' </div>\n'+
'<PanelBody>\n'+
'  <div className="d-flex align-items-center justify-content-center">\n'+
'    <div className="me-1">Go to page:</div>\n'+
'    <div className="w-50px mx-2 me-auto">\n'+
'      <input className="form-control" type="number" defaultValue={pageIndex + 1}\n'+
'          onChange={e => {\n'+
'            const page = e.target.value ? Number(e.target.value) - 1 : 0\n'+
'            gotoPage(page)\n'+
'          }}\n'+
'        />\n'+
'    </div>\n'+
'    <ul className="pagination mb-0">\n'+
'      <li className="page-item"><button className="page-link" onClick={() => gotoPage(0)} disabled={!canPreviousPage}><i className="fa fa-angle-double-left"></i></button></li>\n'+
'      <li className="page-item"><button className="page-link" onClick={() => previousPage()} disabled={!canPreviousPage}><i className="fa fa-angle-left"></i></button></li>\n'+
'      <li className="page-item d-flex align-items-center px-2">\n'+
'        <div>Page <strong>{pageIndex + 1} of {pageOptions.length}</strong></div>\n'+
'      </li>\n'+
'      <li className="page-item"><button className="page-link" onClick={() => nextPage()} disabled={!canNextPage}><i className="fa fa-angle-right"></i></button></li>\n'+
'      <li className="page-item"><button className="page-link" onClick={() => gotoPage(pageCount - 1)} disabled={!canNextPage}><i className="fa fa-angle-double-right"></i></button></li>\n'+
'    </ul>\n'+
'  </div>\n'+
'</PanelBody>'}
					</Highlight>
				</div>
			</Panel>
		</div>
	)
}

export default TableData;