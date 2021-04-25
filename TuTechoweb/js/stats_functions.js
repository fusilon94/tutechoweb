

const paleta_colores = ['rgb(251,201,59.0.8)', 'rgba(222,45,38,0.8)', 'rgb(rgb(0,166,255,0.8))', 'rgb(172,246,87,0.8)'];
const paleta_colores_reverse = ['rgb(172,246,87,0.8)', 'rgb(rgb(0,166,255,0.8))', 'rgba(222,45,38,0.8)', 'rgb(251,201,59.0.8)'];


// ############################### NAVEGACION ######################################

// BOTON SIMPLE
function boton_simple(btn_text, btn_class, activo){
  return `<span class="boton_simple ${btn_class} ${activo}">${btn_text}</span>`;
};

function radio_butons(clase_comun, data, activo_choice){

    let check_box_radio_group = `<span class ="check_box_radio_group">`;
   
    const key_val = Object.entries(data);
    for ([text, value] of key_val) {
      check_box_radio_group += `<span class="radio_btn ${clase_comun} ${(value == activo_choice ? 'activo' : '')}" data="${value}">${text}</span>`;
    };

    check_box_radio_group += `</span>`;

    return check_box_radio_group;

};

function get_z_scores(CONTENEDOR){
    const trace_mean = CONTENEDOR.querySelector(".barlayer.mlayer").querySelectorAll(".trace.bars")[0];
    const barras_mean = trace_mean.querySelectorAll(".point");
    const barras_personal = CONTENEDOR.querySelector(".barlayer.mlayer").querySelectorAll(".trace.bars")[1].querySelectorAll(".point");
    let promedios = [];
    for (const barra of barras_mean) {
      promedios.push(barra.__data__.y);
    };
    let desviaciones_estandar = trace_mean.querySelector(".point").__data__.trace.error_y.array;
    let valores_personal = [];
    for (const barra of barras_personal) {
      valores_personal.push(barra.__data__.y);
    };

    let count_iterations = valores_personal.length - 1;
    let count = 0;
    let text_list = []; 
    while (count <= count_iterations) {
      const z_core = ((valores_personal[count] - promedios[count])/desviaciones_estandar[count]).toFixed(3);

      if (z_core < (-3)) {
        text_list.push("***(>3_SD)");
      }else if(z_core < (-2)){
        text_list.push("**(>2_SD)");
      }else if(z_core < (-1)){
        text_list.push("*(>SD)");
      }else{
        text_list.push("");
      }
      count += 1;
    };

    return text_list;
}


// ############################### FUNCIONES ######################################
// HISTOGRAMAS @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
function histograma_count_total(CONTENEDOR, trace, title, y_axis){

    const data = [{
        x: trace,
        type: 'histogram',
        xbins: {size: 'M1'},
        automargin: true,
        histfunc: 'count',
        marker: {
          color: paleta_colores[0]
        }
      }];

    const layout = {
        title: {text: title},
        xaxis: {
            type: 'date',
            rangeslider: {visible: false, thickness: 0.1},
            rangeselector: {buttons: [
                {
                count: 3,
                label: '3 meses',
                step: 'month',
                stepmode: 'backward'
                },
                {
                count: 1,
                label: '1 año',
                step: 'year',
                stepmode: 'backward'
                },
                {
                step: 'all',
                label: 'TODO'
                }
            ]},
        },
        yaxis: {
          title: {text: y_axis}
        },
        height : 380,
        margin : {b : 50, r: 30, l: 30, t: 80}
    }

    const extras = {displayModeBar: true, displaylogo: false, responsive: true, scrollZoom: true, modeBarButtonsToRemove: ['zoom2d', 'lasso2d', 'zoomIn2d', 'zoomOut2d', 'autoScale2d', 'toggleSpikelines', 'hoverClosestCartesian', 'hoverCompareCartesian']};

    
    Plotly.newPlot(CONTENEDOR, data, layout, extras);

};
// Histograma sum con x y
function histograma_sum_total(CONTENEDOR, trace, title, y_axis){

  const data = [{
      x: trace[0],
      y: trace[1],
      type: 'histogram',
      xbins: {size: 'M1'},
      automargin: true,
      histfunc: 'sum',
      marker: {
        color: paleta_colores[0]
      }
    }];

  const layout = {
      title: {text: title},
      xaxis: {
          type: 'date',
          rangeslider: {visible: false, thickness: 0.1},
          rangeselector: {buttons: [
              {
              count: 3,
              label: '3 meses',
              step: 'month',
              stepmode: 'backward'
              },
              {
              count: 1,
              label: '1 año',
              step: 'year',
              stepmode: 'backward'
              },
              {
              step: 'all',
              label: 'TODO'
              }
          ]},
      },
      yaxis: {
        title: {text: y_axis}
      },
      height : 380,
      margin : {b : 50, r: 50, l: 50, t: 80}
  }

  const extras = {displayModeBar: true, displaylogo: false, responsive: true, scrollZoom: true, modeBarButtonsToRemove: ['zoom2d', 'lasso2d', 'zoomIn2d', 'zoomOut2d', 'autoScale2d', 'toggleSpikelines', 'hoverClosestCartesian', 'hoverCompareCartesian']};

  
  Plotly.newPlot(CONTENEDOR, data, layout, extras);

};

// Stacked Histogram @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
function histograma_count_stacked(CONTENEDOR, traces, title, y_axis){

  let data = [];
  const key_val = Object.entries(traces);
  let count_color = 0;
  for ([key, trace] of key_val) {
    data.push({
      name: key,
      x: trace,
      type: 'histogram',
      xbins: {size: 'M1'},
      automargin: true,
      marker: {
        color: paleta_colores[count_color]
      }
    })

    count_color += 1;

  };

  const layout = {
      title: {text: title},
      barmode: 'stack',
      xaxis: {
          type: 'date',
          rangeslider: {visible: false, thickness: 0.1},
          rangeselector: {buttons: [
              {
              count: 3,
              label: '3 meses',
              step: 'month',
              stepmode: 'backward'
              },
              {
              count: 1,
              label: '1 año',
              step: 'year',
              stepmode: 'backward'
              },
              {
              step: 'all',
              label: 'TODO'
              }
          ]},
      },
      yaxis: {
        title: {text: y_axis}
      },
      height : 380,
      margin : {b : 50, r: 50, l: 50, t: 80}
  }

  const extras = {displayModeBar: true, displaylogo: false, responsive: true, scrollZoom: true, modeBarButtonsToRemove: ['zoom2d', 'lasso2d', 'zoomIn2d', 'zoomOut2d', 'autoScale2d', 'toggleSpikelines', 'hoverClosestCartesian', 'hoverCompareCartesian']};

  
  Plotly.newPlot(CONTENEDOR, data, layout, extras);


};

function histograma_sum_stacked(CONTENEDOR, traces, title, y_axis){

  let data = [];
  const key_val = Object.entries(traces);
  let count_color = 0;
  for ([key, trace] of key_val) {

    data.push({
      name: key,
      x: trace[0],
      y: trace[1],
      type: 'histogram',
      xbins: {size: 'M1'},
      automargin: true,
      histfunc: 'sum',
      marker: {
        color: paleta_colores[count_color]
      }
    })

    count_color += 1;

  };

  const layout = {
      title: {text: title},
      barmode: 'stack',
      xaxis: {
          type: 'date',
          rangeslider: {visible: false, thickness: 0.1},
          rangeselector: {buttons: [
              {
              count: 3,
              label: '3 meses',
              step: 'month',
              stepmode: 'backward'
              },
              {
              count: 1,
              label: '1 año',
              step: 'year',
              stepmode: 'backward'
              },
              {
              step: 'all',
              label: 'TODO'
              }
          ]},
      },
      yaxis: {
        title: {text: y_axis}
      },
      height : 380,
      margin : {b : 50, r: 50, l: 50, t: 80}
  }

  const extras = {displayModeBar: true, displaylogo: false, responsive: true, scrollZoom: true, modeBarButtonsToRemove: ['zoom2d', 'lasso2d', 'zoomIn2d', 'zoomOut2d', 'autoScale2d', 'toggleSpikelines', 'hoverClosestCartesian', 'hoverCompareCartesian']};

  
  Plotly.newPlot(CONTENEDOR, data, layout, extras);


};

//OVERLAYED HISTOGRAM
function histograma_count_overlayed_fixed_tolerance(CONTENEDOR, traces, labels, title, y_axis){

  const data = [{
    x: traces[0],
    y: traces[1],
    name: labels[0],
    type: 'histogram',
    xbins: {size: 'M1'},
    automargin: true,
    opacity: 0.7,
    marker: { color: 'rgba(255, 100, 102, 0.7)' },
    histfunc: 'sum',
    error_y: {
      type: 'constant',
      symmetric: false,
      value: 0,
      valueminus: 3,
      color: '#85144B',
      thickness: 3,
      visible: true
    },
  },
  {
    x: traces[2],
    name: labels[1],
    type: 'histogram',
    xbins: {size: 'M1'},
    automargin: true,
    histfunc: 'count',
    opacity: 0.95,
    marker: { color: 'rgba(100, 200, 102, 0.7)' },
  }];

  const layout = {
      title: {text: title},
      barmode: 'overlay',
      xaxis: {
          type: 'date',
          rangeslider: {visible: false, thickness: 0.1},
          rangeselector: {buttons: [
              {
              count: 3,
              label: '3 meses',
              step: 'month',
              stepmode: 'backward'
              },
              {
              count: 1,
              label: '1 año',
              step: 'year',
              stepmode: 'backward'
              },
              {
              step: 'all',
              label: 'TODO'
              }
          ]},
      },
      yaxis: {
        title: {text: y_axis}
      },
      height : 380,
      margin : {b : 50, r: 50, l: 50, t: 80}
  }

  const extras = {displayModeBar: true, displaylogo: false, responsive: true, scrollZoom: true, modeBarButtonsToRemove: ['zoom2d', 'lasso2d', 'zoomIn2d', 'zoomOut2d', 'autoScale2d', 'toggleSpikelines', 'hoverClosestCartesian', 'hoverCompareCartesian']};


  Plotly.newPlot(CONTENEDOR, data, layout, extras)

};

function histograma_count_overlayed_standard_deviation(CONTENEDOR, traces, labels, title, y_axis, std_list){

  const data = [{
    x: traces[0],
    y: traces[1],
    name: labels[0],
    type: 'histogram',
    xbins: {size: 'M1'},
    automargin: true,
    histfunc: 'sum',
    opacity: 0.7,
    text: ["", ""],
    marker: { color: 'rgba(255, 100, 102, 0.7)' },
    error_y: {
      type: 'data',
      array: std_list,
      color: '#85144B',
      thickness: 3,
      visible: true
    },
  },
  {
    x: traces[2],
    y: traces[3],
    name: labels[1],
    type: 'histogram',
    xbins: {size: 'M1'},
    automargin: true,
    histfunc: 'sum',
    opacity: 0.95,
    marker: { color: 'rgba(100, 200, 102, 0.7)' },
  }];

  const layout = {
      title: {text: title},
      barmode: 'overlay',
      hoverlabel: {align: 'right'},
      xaxis: {
          type: 'date',
          rangeslider: {visible: false, thickness: 0.1},
          rangeselector: {buttons: [
              {
              count: 3,
              label: '3 meses',
              step: 'month',
              stepmode: 'backward'
              },
              {
              count: 1,
              label: '1 año',
              step: 'year',
              stepmode: 'backward'
              },
              {
              step: 'all',
              label: 'TODO'
              }
          ]},
      },
      yaxis: {
        title: {text: y_axis}
      },
      height : 380,
      margin : {b : 50, r: 50, l: 50, t: 80}
  }

  const extras = {displayModeBar: true, displaylogo: false, responsive: true, scrollZoom: true, modeBarButtonsToRemove: ['zoom2d', 'lasso2d', 'zoomIn2d', 'zoomOut2d', 'autoScale2d', 'toggleSpikelines', 'hoverClosestCartesian', 'hoverCompareCartesian']};

  Plotly.newPlot(CONTENEDOR, data, layout, extras).then(plot => {
    // console.log(plot.data);
    // console.log(plot.layout);
    // console.log(plot._fullLayout);
    const text_list = get_z_scores(CONTENEDOR);

    Plotly.restyle(CONTENEDOR, {text: [text_list, ""]});
});

};

// BAR CHART @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

function crear_bar_chart(CONTENEDOR, labels, values, title, y_axis){

  const data = [{
    x: labels,
    y: values,
    type: 'bar',
    automargin: true,
    textfont: { size: 15 },
    marker:{
      color: paleta_colores,
    },
  }];
  
  const layout = {
    title: { text: title },
    autosize: true,
    height : 380,
    margin : {b : 50, r: 50, l: 50, t: 80},
    yaxis: {
      title: {text: y_axis}
    },
  };

  const extras = {displayModeBar: true, displaylogo: false, responsive: true, scrollZoom: true, modeBarButtonsToRemove: ['zoom2d', 'lasso2d', 'zoomIn2d', 'zoomOut2d', 'autoScale2d', 'toggleSpikelines', 'hoverClosestCartesian', 'hoverCompareCartesian', 'select2d']}

  Plotly.newPlot(CONTENEDOR, data, layout, extras);

};

function crear_bar_chart_vertical(CONTENEDOR, labels, values, title, x_axis){

  const data = [{
    x: values,
    y: labels,
    type: 'bar',
    automargin: true,
    textfont: { size: 15 },
    orientation: "h",
    marker:{
      color: paleta_colores_reverse,
    },
  }];
  
  const layout = {
    title: { text: title },
    autosize: true,
    height : 370,
    margin : {b : 50, r: 110, l: 110, t: 80},
    xaxis: {
      title: {text: x_axis}
    },
  };

  const extras = {displayModeBar: true, displaylogo: false, responsive: true, scrollZoom: true, modeBarButtonsToRemove: ['zoom2d', 'lasso2d', 'zoomIn2d', 'zoomOut2d', 'autoScale2d', 'toggleSpikelines', 'hoverClosestCartesian', 'hoverCompareCartesian', 'select2d']}

  Plotly.newPlot(CONTENEDOR, data, layout, extras);

};

function crear_bar_chart_std(CONTENEDOR, labels, values, std_list, title, y_axis){

  const data = [{
    x: labels,
    y: values,
    type: 'bar',
    automargin: true,
    textfont: { size: 15 },
    error_y: {
      type: 'data',
      array: std_list,
      color: '#444444',
      thickness: 3,
      visible: true
    },
    marker:{
      color: paleta_colores
    },
  }];
  
  const layout = {
    title: { text: title },
    autosize: true,
    showlegend: false,
    height : 380,
    margin : {b : 50, r: 50, l: 50, t: 80},
    yaxis: {
      title: {text: y_axis}
    },
  };

  const extras = {displayModeBar: true, displaylogo: false, responsive: true, scrollZoom: true, modeBarButtonsToRemove: ['zoom2d', 'lasso2d', 'zoomIn2d', 'zoomOut2d', 'autoScale2d', 'toggleSpikelines', 'hoverClosestCartesian', 'hoverCompareCartesian', 'select2d']}

  Plotly.newPlot(CONTENEDOR, data, layout, extras);

};

function crear_bar_chart_grouped(CONTENEDOR, labels, traces, title, y_axis){

  let data = [];
  const key_val = Object.entries(traces);
  let count_color = 0;

  for ([key, trace] of key_val) {

    data.push({
      name: key,
      x: labels,
      y: trace,
      type: 'bar',
      automargin: true,
      textfont: { size: 12 },
      textposition: 'auto',
      hoverinfo: 'y',
      marker: {
        color: paleta_colores[count_color]
      }
    })

    count_color += 1;

  };
  
  const layout = {
    barmode: 'group',
    title: { text: title },
    autosize: true,
    height : 380,
    margin : {b : 50, r: 50, l: 50, t: 80},
    yaxis: {
      title: {text: y_axis}
    },
  };

  const extras = {displayModeBar: true, displaylogo: false, responsive: true, scrollZoom: true, modeBarButtonsToRemove: ['zoom2d', 'lasso2d', 'zoomIn2d', 'zoomOut2d', 'autoScale2d', 'toggleSpikelines', 'hoverClosestCartesian', 'hoverCompareCartesian', 'select2d']}

  Plotly.newPlot(CONTENEDOR, data, layout, extras).then(plot => {
    
    const texts = Object.keys(traces);

    Plotly.restyle(CONTENEDOR, {text: (texts.map(String)),});
});;

};


//LINE PLOTS @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

function crear_line_plot(CONTENEDOR, traces, title, y_axis){

  let data = [];
  const key_val = Object.entries(traces);
  let count_color = 0;

  for ([key, trace] of key_val) {

    data.push({
      name: key,
      x: trace[0],
      y: trace[1],
      mode: 'lines',
      type: 'scatter',
      automargin: true,
      hoverinfo: 'y',
      line: {
        smoothing: 0.5,
        shape: 'spline',
        width: 3
      },
      marker: {
        color: paleta_colores[count_color]
      }
    });

    count_color += 1;

  };

  const layout = {
    title: { text: title },
    autosize: true,
    height : 370,
    margin : {b : 50, r: 50, l: 50, t: 80},
    xaxis: {
      type: 'date',
      rangeslider: {visible: false, thickness: 0.1},
      rangeselector: {buttons: [
          {
          count: 3,
          label: '3 meses',
          step: 'month',
          stepmode: 'backward'
          },
          {
          count: 1,
          label: '1 año',
          step: 'year',
          stepmode: 'backward'
          },
          {
          step: 'all',
          label: 'TODO'
          }
      ]},
    },
    yaxis: {
      title: {text: y_axis},
      spikecolor: '#4a4848',
    },
  };

  const extras = {displayModeBar: true, displaylogo: false, responsive: true, scrollZoom: true, modeBarButtonsToRemove: ['zoom2d', 'lasso2d', 'zoomIn2d', 'zoomOut2d', 'autoScale2d', 'hoverClosestCartesian', 'hoverCompareCartesian', 'select2d']}

  Plotly.newPlot(CONTENEDOR, data, layout, extras);

};


// PIE CHART @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

function crear_pie(CONTENEDOR, values, labels, title){

    const data = [{
      values: values,
      labels: labels,
      type: 'pie',
      textinfo: "label+percent",
      automargin: true,
      textfont: { size: 15 },
      marker:{
        colors: paleta_colores,
      }
    }];
    
    const layout = {
      title: { text: title },
      margin: { pad : 10, t : 60, b : 20 },
      autosize: true,
      showlegend: false,
      height : 380,
    };

    const extras = {displayModeBar: true, displaylogo: false, responsive: true, scrollZoom: true, modeBarButtonsToRemove: ['hoverClosestPie']}

    Plotly.newPlot(CONTENEDOR, data, layout, extras);

};

// DONUT CHART @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

function crear_donut(CONTENEDOR, values, labels, title, center_label){

  const data = [{
    values: values,
    labels: labels,
    hole: .5,
    type: 'pie',
    textinfo: "label+percent",
    automargin: true,
    textfont: {size: 15},
    insidetextorientation: "horizontal",
    marker:{
      colors: paleta_colores,
    }
  }];

  const layout = {
    title: { 
      text: title, 
      x: 0.5,
      y: 0.93
    },
    margin: { pad : 10, t : 60, b : 20 },
    autosize: true,
    showlegend: false,
    height: 380,
    annotations: [{
        font: { size: 20 },
        showarrow: false,
        text: `${center_label}`,
        x: 0.5,
        y: 0.5
    }]
  };

  const extras = {displayModeBar: true, displaylogo: false, responsive: true, scrollZoom: true, modeBarButtonsToRemove: ['hoverClosestPie']}

  Plotly.newPlot(CONTENEDOR, data, layout, extras);

};