﻿using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Logging;
using ColorAdmin.Models;

namespace ColorAdmin.Controllers
{
    public class HelperController : Controller
    {

        public IActionResult Css()
        {
            return View();
        }
    }
}
