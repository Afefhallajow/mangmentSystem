package mangemtSystemJava.Controller;

import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

@RestController
@RequestMapping("/get")
public class testController {

    @GetMapping("/a")
    public String test() {
        return "hello from spring boot";
    }
}
